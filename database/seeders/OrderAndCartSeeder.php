<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;

class OrderAndCartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userAndi = User::where('email', 'andi@gmail.com')->first();
        if (!$userAndi) return;

        // Get 4 distinct products
        $products = Product::inRandomOrder()->take(4)->get();
        if ($products->count() < 4) return;

        // Add 2 items to Cart
        Cart::firstOrCreate([
            'user_id' => $userAndi->id,
            'product_id' => $products[0]->id,
        ], ['quantity' => 1]);

        Cart::firstOrCreate([
            'user_id' => $userAndi->id,
            'product_id' => $products[1]->id,
        ], ['quantity' => 2]);

        // Create 1 past Order for Andi
        $subtotal = ($products[2]->price * 1) + ($products[3]->price * 2);
        $ongkir = 15000;
        $ppn = $subtotal * 0.01;

        $order = Order::create([
            'user_id' => $userAndi->id,
            'address' => 'Jl. Pahlawan No. 12, Jakarta',
            'total_price' => $subtotal + $ongkir + $ppn,
            'status' => 'paid',
            'created_at' => now()->subDays(2),
            'updated_at' => now()->subDays(2),
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $products[2]->id,
            'quantity' => 1,
            'price' => $products[2]->price,
        ]);

        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $products[3]->id,
            'quantity' => 2,
            'price' => $products[3]->price,
        ]);
    }
}
