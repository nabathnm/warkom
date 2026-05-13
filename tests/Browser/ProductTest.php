<?php

namespace Tests\Browser;

use App\Models\Product;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ProductTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_view_products_on_homepage()
    {
        $user = \App\Models\User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Awesome Coffee',
            'price' => 25000,
        ]);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                    ->visit('/products')
                    ->assertSee('Awesome Coffee');
        });
    }

    public function test_user_can_view_product_details()
    {
        $user = \App\Models\User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Delicious Pastry',
            'description' => 'A very tasty pastry',
            'price' => 15000,
        ]);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                    ->visit('/products/' . $product->id)
                    ->assertSee('Delicious Pastry')
                    ->assertSee('A very tasty pastry');
        });
    }
}
