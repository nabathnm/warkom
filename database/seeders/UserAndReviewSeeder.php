<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin 1
        $admin = User::firstOrCreate(
            ['email' => 'admin1@gmail.com'],
            [
                'name' => 'Admin 1',
                'password' => Hash::make('123456'),
                'role' => 'admin'
            ]
        );

        // Create User Andi
        $userAndi = User::firstOrCreate(
            ['email' => 'andi@gmail.com'],
            [
                'name' => 'Andi',
                'password' => Hash::make('123456'),
                'role' => 'user'
            ]
        );

        // Fetch some products to leave reviews
        $products = Product::take(5)->get();

        if ($products->count() > 0) {
            // Admin leaves reviews
            foreach ($products->take(2) as $product) {
                Review::firstOrCreate(
                    ['user_id' => $admin->id, 'product_id' => $product->id],
                    [
                        'rating' => 5,
                        'comment' => 'Produk ini sangat bagus, stok selalu tersedia. Recommended!'
                    ]
                );
            }

            // User Andi leaves reviews
            foreach ($products->skip(2)->take(3) as $product) {
                Review::firstOrCreate(
                    ['user_id' => $userAndi->id, 'product_id' => $product->id],
                    [
                        'rating' => 4,
                        'comment' => 'Barangnya mantap, harga bersahabat. Pengiriman juga cepat. Terima kasih!'
                    ]
                );
            }
        }
    }
}
