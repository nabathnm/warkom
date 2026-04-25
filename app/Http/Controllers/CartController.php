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

        $qtyToAdd = (int) $request->input('quantity', 1);
        if ($qtyToAdd < 1) $qtyToAdd = 1;

        if ($cart) {
            // Cek apakah total kuantitas melebihi stok
            if ($cart->quantity + $qtyToAdd > $product->stock) {
                $qtyToAdd = $product->stock - $cart->quantity;
            }
            if ($qtyToAdd > 0) {
                $cart->increment('quantity', $qtyToAdd);
            } else {
                return redirect()->route('cart.index')->with('error', 'Jumlah produk di keranjang sudah mencapai batas maksimum stok.');
            }
        } else {
            // Cek jika kuantitas melebihi stok
            if ($qtyToAdd > $product->stock) {
                $qtyToAdd = $product->stock;
            }
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $qtyToAdd
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
