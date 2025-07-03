<?php

namespace App\Http\Controllers\apps;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += 1;
        } else {
            $cart[$product->id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image_path ?? '/placeholder.svg'
            ];
        }

        session()->put('cart', $cart);
        return back()->with('success', 'Product added to cart!');
    }

    public function remove($id)
    {
        $cart = session()->get('cart', []);
        unset($cart[$id]);
        session()->put('cart', $cart);
        return back()->with('success', 'Item removed from cart.');
    }

    public function view()
    {
        return view('cart.view'); // create cart.view.blade.php
    }

    public function checkout()
    {
        return view('checkout.index'); // create checkout.index.blade.php
    }

    public function applyCoupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->coupon)->first();

        if (!$coupon || ($coupon->expires_at && now()->gt($coupon->expires_at))) {
            return back()->withErrors(['coupon' => 'Invalid or expired coupon']);
        }

        session()->put('coupon', $coupon);
        return back()->with('success', 'Coupon applied!');
    }
    public function getTotal()
    {
        $cart = session()->get('cart', []);
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        if (session()->has('coupon')) {
            $coupon = session('coupon');
            if ($coupon->type === 'percent') {
                $total -= ($total * $coupon->discount / 100);
            } else {
                $total -= $coupon->discount;
            }
        }

        return max($total, 0); // Ensure no negative total
    }
}
