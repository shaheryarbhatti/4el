<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function isAjax(Request $request): bool
    {
        return $request->ajax() || $request->wantsJson();
    }

    /* ── Helpers ── */

    private function cartItemFromProduct(Product $product, int $qty): array
    {
        $img  = $product->primaryImage;
        $path = $img ? $img->path : null;
        $src  = $path
            ? (str_starts_with($path, 'frontend-assets/') ? asset($path) : asset('storage/' . $path))
            : asset('frontend-assets/images/demoes/demo36/products/product-1.jpg');

        return [
            'product_id'     => $product->id,
            'name'           => $product->name,
            'slug'           => $product->slug,
            'price'          => (float) $product->effective_price,
            'original_price' => (float) $product->price,
            'is_on_sale'     => $product->is_on_sale,
            'image'          => $src,
            'free_shipping'  => $product->free_shipping,
            'shipping_cost'  => (float) ($product->shipping_cost ?? 0),
            'condition'      => $product->condition ?? 'new',
            'vendor_name'    => $product->vendor?->name ?? 'Market Seller',
            'quantity'       => $qty,
            'stock'          => (int) $product->stock,
        ];
    }

    /* ── Cart page ── */

    public function index()
    {
        $cart   = session('cart', []);
        $coupon = session('coupon');
        return view('frontend.cart', compact('cart', 'coupon'));
    }

    /* ── Add to cart ── */

    public function add(Request $request, Product $product)
    {
        abort_if($product->status !== 'approved', 404);

        $qty  = max(1, (int) $request->input('quantity', 1));
        $cart = session('cart', []);
        $key  = $product->id;

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = min($cart[$key]['quantity'] + $qty, $product->stock);
        } else {
            $product->load('primaryImage');
            $cart[$key] = $this->cartItemFromProduct($product, $qty);
        }

        session(['cart' => $cart]);
        $count = array_sum(array_column($cart, 'quantity'));

        if ($this->isAjax($request)) {
            return response()->json(['success' => true, 'count' => $count]);
        }

        return redirect()->route('cart.index')->with('cart_added', $product->name . ' added to cart.');
    }

    /* ── Update quantity ── */

    public function update(Request $request)
    {
        $productId = (int) $request->input('product_id');
        $qty       = max(1, (int) $request->input('quantity', 1));
        $cart      = session('cart', []);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = min($qty, $cart[$productId]['stock']);
            session(['cart' => $cart]);
        }

        $subtotal = $this->calcSubtotal($cart);
        $coupon   = session('coupon');
        $discount = $coupon ? min((float)$coupon['discount'], $subtotal) : 0;

        if ($this->isAjax($request)) {
            return response()->json([
                'success'   => true,
                'count'     => array_sum(array_column($cart, 'quantity')),
                'subtotal'  => number_format($subtotal, 2),
                'discount'  => number_format($discount, 2),
                'total'     => number_format(max(0, $subtotal - $discount), 2),
                'itemTotal' => isset($cart[$productId])
                    ? number_format($cart[$productId]['price'] * $cart[$productId]['quantity'], 2)
                    : '0.00',
            ]);
        }

        return back();
    }

    /* ── Remove item ── */

    public function remove(Request $request, int $productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);

        $subtotal = $this->calcSubtotal($cart);
        $coupon   = session('coupon');
        $discount = $coupon ? min((float)$coupon['discount'], $subtotal) : 0;

        if ($this->isAjax($request)) {
            return response()->json([
                'success'  => true,
                'count'    => array_sum(array_column($cart, 'quantity')),
                'subtotal' => number_format($subtotal, 2),
                'discount' => number_format($discount, 2),
                'total'    => number_format(max(0, $subtotal - $discount), 2),
                'empty'    => count($cart) === 0,
            ]);
        }

        return back()->with('cart_removed', 'Item removed from cart.');
    }

    /* ── Clear entire cart ── */

    public function clear()
    {
        session()->forget(['cart', 'coupon']);
        return redirect()->route('cart.index');
    }

    /* ── Apply coupon ── */

    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->input('coupon_code', '')));

        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Please enter a coupon code.']);
        }

        $cart     = session('cart', []);
        $subtotal = $this->calcSubtotal($cart);

        if ($subtotal <= 0) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Invalid coupon code.']);
        }

        $valid = $coupon->isValid($subtotal);
        if ($valid !== true) {
            return response()->json(['success' => false, 'message' => $valid]);
        }

        $discount = $coupon->calcDiscount($subtotal);

        session(['coupon' => [
            'id'       => $coupon->id,
            'code'     => $coupon->code,
            'type'     => $coupon->type,
            'value'    => $coupon->value,
            'discount' => $discount,
        ]]);

        return response()->json([
            'success'  => true,
            'code'     => $coupon->code,
            'discount' => number_format($discount, 2),
            'total'    => number_format(max(0, $subtotal - $discount), 2),
            'message'  => 'Coupon applied successfully!',
        ]);
    }

    /* ── Remove coupon ── */

    public function removeCoupon(Request $request)
    {
        session()->forget('coupon');
        $subtotal = $this->calcSubtotal(session('cart', []));

        return response()->json([
            'success'  => true,
            'subtotal' => number_format($subtotal, 2),
            'total'    => number_format($subtotal, 2),
        ]);
    }

    /* ── Helpers ── */

    private function calcSubtotal(array $cart): float
    {
        $total = 0.0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }
}
