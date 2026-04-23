<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->with('items.product')->orderBy('created_at', 'desc')->get();
        return view('orders', compact('orders'));
    }

    public function checkout()
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        return view('checkout', compact('carts'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:1000',
        ]);

        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('products.index')->with('error', 'Keranjang belanja Anda kosong.');
        }

        // Calculate total
        $totalPrice = 0;
        foreach ($carts as $cart) {
            $totalPrice += $cart->product->price * $cart->quantity;
            
            // Check stock again
            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $cart->product->name . ' tidak mencukupi.');
            }
        }

        // Create Order
        $order = Order::create([
            'user_id' => Auth::id(),
            'address' => $request->address,
            'total_price' => $totalPrice,
            'status' => 'paid',
        ]);

        // Create Order Items and decrease stock
        foreach ($carts as $cart) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart->product_id,
                'quantity' => $cart->quantity,
                'price' => $cart->product->price,
            ]);

            $cart->product->decrement('stock', $cart->quantity);
        }

        // Clear Cart
        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('success');
    }
}
