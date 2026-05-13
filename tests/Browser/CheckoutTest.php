<?php

namespace Tests\Browser;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_checkout()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'price' => 50000,
            'stock' => 5,
        ]);

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/cart')
                    ->press('Checkout')
                    ->assertPathIs('/checkout')
                    ->type('address', 'Jalan Mawar Merah No 1')
                    ->press('Pesan sekarang')
                    ->assertPathIs('/checkout/success');
        });
    }
}
