<?php

namespace Tests\Browser;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CartTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_add_product_to_cart()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create([
            'name' => 'Caramel Macchiato',
            'price' => 30000,
            'stock' => 10,
        ]);

        $this->browse(function (Browser $browser) use ($user, $product) {
            $browser->loginAs($user)
                    ->visit('/products/' . $product->id)
                    ->press('Tambah ke Keranjang')
                    ->assertPathIs('/cart')
                    ->assertSee('Caramel Macchiato');
        });
    }

    public function test_guest_is_redirected_to_login_when_adding_to_cart()
    {
        $product = Product::factory()->create();

        $this->browse(function (Browser $browser) {
            $browser->visit('/cart')
                    ->assertPathIs('/login');
        });
    }
}
