<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\TaxClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('draw')) {
            $query = Product::with(['category', 'vendor', 'primaryImage', 'brand'])
                ->select('products.*');

            if ($request->filled('q')) {
                $term = '%'.$request->q.'%';
                $query->where(fn ($w) => $w->where('name', 'like', $term)->orWhere('sku', 'like', $term));
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('category')) {
                $query->where('category_id', $request->category);
            }
            if ($request->filled('listing_type')) {
                $query->where('listing_type', $request->listing_type);
            }
            if ($request->filled('brand')) {
                $query->where('brand_id', $request->brand);
            }

            $query->latest();

            return DataTables::of($query)
                ->addColumn('product_html', function (Product $p) {
                    $img = $p->primaryImage;
                    if ($img) {
                        $imgSrc = str_starts_with($img->path, 'frontend-assets/') ? asset($img->path) : asset('storage/'.$img->path);
                        $thumb = '<img src="'.$imgSrc.'" class="prod-thumb" alt="">';
                    } else {
                        $initial = mb_strtoupper(mb_substr($p->name, 0, 1));
                        $hue     = abs(crc32($p->name)) % 360;
                        $thumb   = '<div class="prod-avatar" style="--av-hue:'.$hue.'">'.$initial.'</div>';
                    }
                    $sku = $p->sku ? '<span class="prod-sku">'.e($p->sku).'</span>' : '';
                    return '<div class="prod-cell">'.$thumb
                        .'<div class="prod-info"><span class="prod-name">'.e(Str::limit($p->name, 42)).'</span>'.$sku.'</div></div>';
                })
                ->addColumn('category_html', function (Product $p) {
                    return $p->category
                        ? '<span class="cat-pill">'.e($p->category->name).'</span>'
                        : '<span class="b-off">—</span>';
                })
                ->addColumn('price_html', function (Product $p) {
                    if ($p->sale_price && $p->is_on_sale) {
                        return '<span class="price-old">$'.number_format($p->price, 2).'</span>'
                              .'<span class="price-sale">$'.number_format($p->sale_price, 2).'</span>';
                    }
                    return '<span class="price-main">$'.number_format($p->price, 2).'</span>';
                })
                ->addColumn('stock_html', function (Product $p) {
                    if ($p->stock == 0) {
                        return '<span class="stock-out"><i class="bi bi-x-circle-fill"></i> '.$p->stock.'</span>';
                    }
                    if ($p->stock < 5) {
                        return '<span class="stock-low"><i class="bi bi-exclamation-triangle-fill"></i> '.$p->stock.'</span>';
                    }
                    return '<span class="stock-ok"><i class="bi bi-check-circle-fill"></i> '.$p->stock.'</span>';
                })
                ->addColumn('status_html', function (Product $p) {
                    $map = [
                        'pending'  => ['b-pending',  'bi-clock-fill',       'Pending'],
                        'approved' => ['b-approved', 'bi-check-circle-fill','Approved'],
                        'rejected' => ['b-rejected', 'bi-x-circle-fill',    'Rejected'],
                    ];
                    [$cls, $icon, $label] = $map[$p->status] ?? ['b-off','bi-dash', ucfirst($p->status)];
                    $hidden = ! $p->is_active
                        ? '<br><span class="b-hidden"><i class="bi bi-eye-slash-fill"></i> Hidden</span>'
                        : '';
                    return '<span class="'.$cls.'"><i class="bi '.$icon.'"></i> '.$label.'</span>'.$hidden;
                })
                ->addColumn('flags_html', function (Product $p) {
                    $out = '';
                    if ($p->is_featured) $out .= '<span class="b-feat"><i class="bi bi-star-fill"></i> Featured</span> ';
                    if ($p->is_deal)     $out .= '<span class="b-deal"><i class="bi bi-fire"></i> Deal</span> ';
                    $lt = $p->listing_type === 'auction'
                        ? '<span class="b-auction"><i class="bi bi-hammer"></i> Auction</span>'
                        : '<span class="b-fixed"><i class="bi bi-tag-fill"></i> Fixed</span>';
                    return $out.$lt;
                })
                ->addColumn('actions', function (Product $p) {
                    $edit = route('admin.products.edit', $p);
                    $del  = route('admin.products.destroy', $p);
                    return '
                        <div class="act-wrap">
                          <a href="'.$edit.'" class="btn-act btn-edit" title="Edit"><i class="bi bi-pencil"></i></a>
                          <form action="'.$del.'" method="POST" onsubmit="return confirm(\'Delete this product?\')">
                            '.csrf_field().''.method_field('DELETE').'
                            <button type="submit" class="btn-act btn-del" title="Delete"><i class="bi bi-trash"></i></button>
                          </form>
                        </div>';
                })
                ->rawColumns(['product_html','category_html','price_html','stock_html','status_html','flags_html','actions'])
                ->make(true);
        }

        $stats = [
            'total'    => Product::count(),
            'approved' => Product::where('status', 'approved')->count(),
            'pending'  => Product::where('status', 'pending')->count(),
            'featured' => Product::where('is_featured', true)->count(),
        ];

        $categories = Category::orderBy('name')->get();
        $brands     = Brand::orderBy('name')->get();

        return view('admin.products.index', compact('stats', 'categories', 'brands'));
    }

    public function create()
    {
        return view('admin.products.form', $this->formData(new Product([
            'status'       => 'approved',
            'is_active'    => true,
            'listing_type' => 'fixed',
            'condition'    => 'new',
            'tax_class_id' => optional(TaxClass::default())->id,
        ])));
    }

    public function store(Request $request)
    {
        $data    = $this->validateData($request);
        $product = Product::create($data);
        $this->syncImages($request, $product);
        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        return view('admin.products.form', $this->formData($product));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateData($request);
        $product->update($data);
        $this->syncImages($request, $product);
        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->path);
        }
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    /* ─── helpers ─── */

    private function formData(Product $product): array
    {
        return [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
            'brands'     => Brand::orderBy('name')->get(),
            'taxClasses' => TaxClass::where('is_active', true)->orderBy('name')->get(),
            'vendors'    => User::role('vendor')->orderBy('name')->get(),
        ];
    }

    private function validateData(Request $request): array
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:255'],
            'sku'               => ['nullable', 'string', 'max:100'],
            'vendor_id'         => ['nullable', 'exists:users,id'],
            'category_id'       => ['nullable', 'exists:categories,id'],
            'brand_id'          => ['nullable', 'exists:brands,id'],
            'condition'         => ['required', 'in:new,used,refurbished'],
            'listing_type'      => ['required', 'in:fixed,auction'],
            'price'             => ['required', 'numeric', 'min:0'],
            'sale_price'        => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'stock'             => ['required', 'integer', 'min:0'],
            'tax_class_id'      => ['nullable', 'exists:tax_classes,id'],
            'shipping_cost'     => ['nullable', 'numeric', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description'       => ['nullable', 'string'],
            'deal_ends_at'      => ['nullable', 'date'],
            'status'            => ['required', 'in:pending,approved,rejected'],
            'images.*'          => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['free_shipping'] = $request->boolean('free_shipping');
        $validated['is_featured']   = $request->boolean('is_featured');
        $validated['is_deal']       = $request->boolean('is_deal');
        $validated['is_active']     = $request->boolean('is_active');
        $validated['shipping_cost'] = $validated['shipping_cost'] ?? 0;

        return $validated;
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
