<?php

namespace Tests\Feature;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_halaman_orders_dapat_ditampilkan(): void
    {
        $response = $this->actingAs($this->user)->get(route('orders.index'));
        $response->assertStatus(200);
        $response->assertViewIs('orders');
        $response->assertViewHas('orders');
    }

    public function test_guest_tidak_bisa_akses_orders(): void
    {
        $response = $this->get(route('orders.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_checkout_tanpa_pilihan_redirect(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout'));
        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
    }

    public function test_checkout_dengan_selected_items(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $response = $this->actingAs($this->user)->get(route('checkout', ['selected' => [$cart->id]]));
        $response->assertStatus(200);
        $response->assertViewIs('checkout');
        $response->assertViewHas('carts');
    }

    public function test_checkout_selected_kosong_redirect(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout', ['selected' => []]));
        $response->assertRedirect(route('cart.index'));
    }

    public function test_process_checkout_berhasil(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 100000]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $response = $this->actingAs($this->user)->post(route('checkout.process'), [
            'address' => 'Jl. Test No. 123',
            'selected' => [$cart->id],
        ]);

        $response->assertRedirect(route('checkout.success'));

        // Verifikasi order dibuat
        $this->assertDatabaseHas('orders', [
            'user_id' => $this->user->id,
            'address' => 'Jl. Test No. 123',
            'status' => 'paid',
        ]);

        // Verifikasi order items
        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertNotNull($order);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100000,
        ]);

        // Verifikasi stok berkurang
        $product->refresh();
        $this->assertEquals(8, $product->stock);

        // Verifikasi cart dihapus
        $this->assertDatabaseMissing('carts', ['id' => $cart->id]);
    }

    public function test_process_kalkulasi_total_harga(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 100000]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 2]);

        $this->actingAs($this->user)->post(route('checkout.process'), [
            'address' => 'Jl. Test',
            'selected' => [$cart->id],
        ]);

        $order = Order::where('user_id', $this->user->id)->first();
        // subtotal = 100000 * 2 = 200000, ongkir = 15000, ppn = 200000*0.01 = 2000
        // total = 200000 + 15000 + 2000 = 217000
        $this->assertEquals(217000, $order->total_price);
    }

    public function test_process_gagal_tanpa_alamat(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->post(route('checkout.process'), [
            'selected' => [$cart->id],
        ]);
        $response->assertSessionHasErrors('address');
    }

    public function test_process_gagal_tanpa_selected(): void
    {
        $response = $this->actingAs($this->user)->post(route('checkout.process'), [
            'address' => 'Jl. Test',
        ]);
        $response->assertSessionHasErrors('selected');
    }

    public function test_process_stok_tidak_cukup(): void
    {
        $product = Product::factory()->create(['stock' => 1, 'price' => 100000]);
        $cart = Cart::create(['user_id' => $this->user->id, 'product_id' => $product->id, 'quantity' => 5]);

        $response = $this->actingAs($this->user)->post(route('checkout.process'), [
            'address' => 'Jl. Test',
            'selected' => [$cart->id],
        ]);

        $response->assertRedirect(route('cart.index'));
        $response->assertSessionHas('error');
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_process_multiple_items(): void
    {
        $p1 = Product::factory()->create(['stock' => 10, 'price' => 50000]);
        $p2 = Product::factory()->create(['stock' => 5, 'price' => 75000]);
        $c1 = Cart::create(['user_id' => $this->user->id, 'product_id' => $p1->id, 'quantity' => 2]);
        $c2 = Cart::create(['user_id' => $this->user->id, 'product_id' => $p2->id, 'quantity' => 1]);

        $response = $this->actingAs($this->user)->post(route('checkout.process'), [
            'address' => 'Jl. Multi',
            'selected' => [$c1->id, $c2->id],
        ]);

        $response->assertRedirect(route('checkout.success'));
        $order = Order::where('user_id', $this->user->id)->first();
        $this->assertEquals(2, $order->items->count());

        $p1->refresh();
        $p2->refresh();
        $this->assertEquals(8, $p1->stock);
        $this->assertEquals(4, $p2->stock);
    }

    public function test_success_page_menampilkan_order(): void
    {
        $product = Product::factory()->create(['stock' => 10, 'price' => 100000]);
        $order = Order::create([
            'user_id' => $this->user->id,
            'address' => 'Jl. Test',
            'total_price' => 217000,
            'status' => 'paid',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => 100000,
        ]);

        $response = $this->actingAs($this->user)->get(route('checkout.success'));
        $response->assertStatus(200);
        $response->assertViewIs('success');
        $response->assertViewHas('order');
    }

    public function test_success_page_redirect_jika_tidak_ada_order(): void
    {
        $response = $this->actingAs($this->user)->get(route('checkout.success'));
        $response->assertRedirect(route('products.index'));
    }

    public function test_orders_hanya_tampilkan_milik_user(): void
    {
        $otherUser = User::factory()->create(['role' => 'user']);
        $product = Product::factory()->create(['stock' => 10]);

        Order::create(['user_id' => $this->user->id, 'address' => 'A', 'total_price' => 100, 'status' => 'paid']);
        Order::create(['user_id' => $otherUser->id, 'address' => 'B', 'total_price' => 200, 'status' => 'paid']);

        $response = $this->actingAs($this->user)->get(route('orders.index'));
        $response->assertViewHas('orders', fn($o) => $o->count() === 1);
    }
}
