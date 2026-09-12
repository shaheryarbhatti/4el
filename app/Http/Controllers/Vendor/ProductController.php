<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductSpecificationValue;
use App\Models\TaxClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

/**
 * Vendor-scoped product management (frontend).
 *
 * KEY DIFFERENCES vs the admin ProductController:
 *   • A vendor only ever sees/edits THEIR OWN products (scoped by vendor_id).
 *   • The vendor cannot choose the store or the approval status.
 *   • New/edited products are forced to status = "pending" so an admin must
 *     approve them before they go live.
 *   • Shipping/tax pre-fill from the vendor's saved defaults.
 */
class ProductController extends Controller
{
    /** Only this vendor's products. */
    private function scoped()
    {
        return Product::where('vendor_id', auth()->id());
    }

    public function index(Request $request)
    {
        $products = $this->scoped()
            ->with('primaryImage')
            ->when($request->filled('q'), fn ($q) => $q->where('name', 'like', '%'.$request->q.'%'))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('vendor.products.index', compact('products'));
    }

    public function create()
    {
        return view('vendor.products.form', $this->formData(new Product([
            'condition'     => 'new',
            'listing_type'  => 'fixed',
            // pre-fill from the vendor's defaults
            'shipping_cost' => auth()->user()->vendorProfile->default_shipping_cost ?? 0,
            'free_shipping' => auth()->user()->vendorProfile->default_free_shipping ?? false,
            'tax_class_id'  => auth()->user()->vendorProfile->default_tax_class_id
                                ?? optional(TaxClass::default())->id,
        ])));
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        // Force ownership + moderation — the vendor cannot set these.
        $data['vendor_id'] = auth()->id();
        $data['status']    = 'pending';

        $product = Product::create($data);
        $this->syncImages($request, $product);
        $this->syncSpecValues($request, $product);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product submitted — it will appear once an admin approves it.');
    }

    public function edit(Product $product)
    {
        $this->authorizeOwner($product);
        $product->load('images', 'specValues');
        return view('vendor.products.form', $this->formData($product));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeOwner($product);

        $data = $this->validateData($request);
        // Re-submitting an edit sends it back to pending review.
        $data['status'] = 'pending';

        $product->update($data);
        $this->syncImages($request, $product);
        $this->syncSpecValues($request, $product);

        return redirect()->route('vendor.products.index')->with('success', 'Product updated and re-submitted for review.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeOwner($product);

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    /* ================= helpers ================= */

    // Block editing another vendor's product.
    private function authorizeOwner(Product $product): void
    {
        abort_unless($product->vendor_id === auth()->id(), 403);
    }

    private function formData(Product $product): array
    {
        // Existing spec values keyed by specification_id for easy Blade access.
        $existingSpecs = $product->exists
            ? $product->specValues->keyBy('specification_id')
            : collect();

        return [
            'product'       => $product,
            'categories'    => Category::where('is_active', true)->orderBy('name')->get(),
            'brands'        => Brand::where('is_active', true)->orderBy('name')->get(),
            'taxClasses'    => TaxClass::where('is_active', true)->orderBy('name')->get(),
            'existingSpecs' => $existingSpecs,
        ];
    }

    private function validateData(Request $request): array
    {
        $isAuction = $request->input('listing_type') === 'auction';

        $rules = [
            'name'                  => ['required', 'string', 'max:255'],
            'sku'                   => ['nullable', 'string', 'max:100'],
            'category_id'           => ['nullable', 'exists:categories,id'],
            'brand_id'              => ['nullable', 'exists:brands,id'],
            'condition'             => ['required', 'in:new,used,refurbished'],
            'condition_description' => ['nullable', 'string', 'max:1000'],
            'listing_type'          => ['required', 'in:fixed,auction'],
            'price'                 => ['nullable', 'numeric', 'min:0'],
            'sale_price'            => ['nullable', 'numeric', 'min:0'],
            'stock'                 => ['required_if:listing_type,fixed', 'integer', 'min:0'],
            'tax_class_id'          => ['nullable', 'exists:tax_classes,id'],
            'shipping_cost'         => ['nullable', 'numeric', 'min:0'],
            'short_description'     => ['nullable', 'string', 'max:500'],
            'description'           => ['nullable', 'string'],
            'images.*'              => ['nullable', 'image', 'max:2048'],
            // Auction fields
            'starting_bid'          => [$isAuction ? 'required' : 'nullable', 'numeric', 'min:0.01'],
            'reserve_price'         => ['nullable', 'numeric', 'min:0'],
            'auction_duration'      => [$isAuction ? 'required' : 'nullable', 'in:1,3,5,7,10'],
        ];

        $validated = $request->validate($rules);

        $validated['free_shipping'] = $request->boolean('free_shipping');
        $validated['is_active']     = true;
        $validated['shipping_cost'] = $validated['shipping_cost'] ?? 0;

        if ($isAuction) {
            // Set auction_ends_at from duration; price defaults to starting_bid.
            $days = (int) ($validated['auction_duration'] ?? 7);
            $validated['auction_ends_at'] = now()->addDays($days);
            $validated['current_bid']     = null;
            $validated['bid_count']       = 0;
            $validated['price']           = $validated['starting_bid'];
            $validated['stock']           = 1;
        }

        return $validated;
    }

    private function syncSpecValues(Request $request, Product $product): void
    {
        $specs = $request->input('specs', []);
        if (empty($specs)) return;

        foreach ($specs as $specId => $value) {
            if (is_array($value)) {
                $value = json_encode(array_values($value));
            }

            ProductSpecificationValue::updateOrCreate(
                ['product_id' => $product->id, 'specification_id' => $specId],
                ['value' => $value]
            );
        }

        // Remove specs that were not submitted (category changed, etc.)
        $product->specValues()
            ->whereNotIn('specification_id', array_keys($specs))
            ->delete();
    }

    private function syncImages(Request $request, Product $product): void
    {
        foreach ((array) $request->input('delete_images', []) as $imageId) {
            $img = $product->images()->find($imageId);
            if ($img) {
                Storage::disk('public')->delete($img->path);
                $img->delete();
            }
        }

        if ($request->hasFile('images')) {
            $manager = new ImageManager(new Driver());
            foreach ($request->file('images') as $file) {
                $filename = 'products/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                $manager->read($file->getPathname())
                    ->cover(800, 800)
                    ->save(storage_path('app/public/' . $filename));
                $product->images()->create(['path' => $filename]);
            }
        }

        if ($primaryId = $request->input('primary_image')) {
            $product->images()->update(['is_primary' => false]);
            $product->images()->where('id', $primaryId)->update(['is_primary' => true]);
        }

        if ($product->images()->exists() && ! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('id')->first()?->update(['is_primary' => true]);
        }
    }
}
