<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('cart', compact('carts'));
    }

    public function add(Request $request, Product $product)
    {
        abort_if(Auth::user()->role === 'admin', 403, 'Admin tidak perlu fitur keranjang.');

        if ($product->stock <= 0) {
            return redirect()->back()->with('error', 'Mohon maaf, stok produk sedang habis.');
        }

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->first();

        if ($cart) {
            $cart->increment('quantity');
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => 1
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request, Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);
        
        $request->validate(['quantity' => 'required|integer|min:1']);
        $cart->update(['quantity' => $request->quantity]);

        return redirect()->route('cart.index');
    }

    public function remove(Cart $cart)
    {
        abort_if($cart->user_id !== Auth::id(), 403);
        $cart->delete();
        
        return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
    }
}
