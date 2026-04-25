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

    public function checkout(Request $request)
    {
        $selectedIds = $request->input('selected', []);
        
        if (empty($selectedIds)) {
            $carts = collect(); // If nothing selected, empty collection
        } else {
            $carts = Cart::where('user_id', Auth::id())->whereIn('id', $selectedIds)->with('product')->get();
        }
        
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Pilih minimal satu produk untuk di-checkout.');
        }

        return view('checkout', compact('carts'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:1000',
            'selected' => 'required|array',
            'selected.*' => 'exists:carts,id'
        ]);

        $carts = Cart::where('user_id', Auth::id())->whereIn('id', $request->selected)->with('product')->get();
        
        if ($carts->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Produk yang dipilih tidak valid.');
        }

        // Calculate total
        $subtotal = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->product->price * $cart->quantity;
            
            // Check stock again
            if ($cart->product->stock < $cart->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $cart->product->name . ' tidak mencukupi.');
            }
        }

        $ongkir = 15000;
        $ppn = $subtotal * 0.01;
        $totalPrice = $subtotal + $ongkir + $ppn;

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

        // Clear processed Carts
        Cart::where('user_id', Auth::id())->whereIn('id', $request->selected)->delete();

        return redirect()->route('checkout.success');
    }

    public function success()
    {
        return view('success');
    }
}
