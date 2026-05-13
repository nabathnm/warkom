<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_halaman_cart_dapat_ditampilkan(): void
    {
        $response = $this->actingAs($this->user)->get(route('cart.index'));
        $response->assertStatus(200);
        $response->assertViewIs('cart');
        $response->assertViewHas('carts');
    }

    public function test_guest_tidak_bisa_akses_cart(): void
    {
        $response = $this->get(route('cart.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_user_bisa_tambah_produk_ke_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $response = $this->actingAs($this->user)->post(route('cart.add', $product), ['quantity' => 2]);
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);
    }

    public function test_tambah_produk_default_quantity_1(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($this->user)->post(route('cart.add', $product));
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    public function test_tambah_produk_stok_habis(): void
    {
        $product = Product::factory()->create(['stock' => 0]);
        $response = $this->actingAs($this->user)->post(route('cart.add', $product));
        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('carts', ['product_id' => $product->id]);
    }

    public function test_tambah_produk_yang_sudah_ada_di_cart_increment(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($this->user)->post(route('cart.add', $product), ['quantity' => 3]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_tambah_melebihi_stok_di_cap(): void
    {
        $product = Product::factory()->create(['stock' => 5]);
        Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 3]);

        $this->actingAs($this->user)->post(route('cart.add', $product), ['quantity' => 10]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 5,
        ]);
    }

    public function test_tambah_sudah_max_stok_error(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 3]);

        $response = $this->actingAs($this->user)->post(route('cart.add', $product), ['quantity' => 1]);
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    public function test_admin_tidak_bisa_tambah_ke_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $response = $this->actingAs($this->admin)->post(route('cart.add', $product));
        $response->assertStatus(403);
    }

    public function test_user_bisa_update_quantity_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->put(route('cart.update', $cart), ['quantity' => 5]);
        $response->assertRedirect(route('cart.index'));
        $this->assertDatabaseHas('carts', ['id' => $cart->id, 'quantity' => 5]);
    }

    public function test_update_cart_validasi_quantity(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->put(route('cart.update', $cart), ['quantity' => 0]);
        $response->assertSessionHasErrors('quantity');
    }

    public function test_user_tidak_bisa_update_cart_orang_lain(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $otherUser->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->put(route('cart.update', $cart), ['quantity' => 5]);
        $response->assertStatus(403);
    }

    public function test_user_bisa_hapus_dari_cart(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->delete(route('cart.remove', $cart));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_user_tidak_bisa_hapus_cart_orang_lain(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $otherUser->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->delete(route('cart.remove', $cart));
        $response->assertStatus(403);
        $this->assertDatabaseHas('carts', ['id' => $cart->id]);
    }

    public function test_quantity_negatif_jadi_1(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $this->actingAs($this->user)->post(route('cart.add', $product), ['quantity' => -5]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);
    }

    public function test_tambah_baru_melebihi_stok_di_cap(): void
    {
        $product = Product::factory()->create(['stock' => 3]);
        $this->actingAs($this->user)->post(route('cart.add', $product), ['quantity' => 10]);
        $this->assertDatabaseHas('carts', [
            'user_id' => $this->user->id,
            'product_id' => $product->id,
            'quantity' => 3,
        ]);
    }
}
