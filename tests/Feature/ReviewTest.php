<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Product $product;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['role' => 'user']);
        $this->product = Product::factory()->create(['stock' => 10]);
    }

    private function createPurchase(): void
    {
        $order = Order::create([
            'user_id' => $this->user->id,
            'address' => 'Jl. Test',
            'total_price' => 100000,
            'status' => 'paid',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $this->product->id,
            'quantity' => 1,
            'price' => $this->product->price,
        ]);
    }

    public function test_user_bisa_review_setelah_beli(): void
    {
        $this->createPurchase();

        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 5,
            'comment' => 'Produk sangat bagus!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Produk sangat bagus!',
        ]);
    }

    public function test_user_tidak_bisa_review_tanpa_beli(): void
    {
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 3,
            'comment' => 'Belum beli',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('reviews', ['user_id' => $this->user->id]);
    }

    public function test_user_tidak_bisa_review_dua_kali(): void
    {
        $this->createPurchase();
        Review::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'rating' => 4,
            'comment' => 'Pertama',
        ]);

        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 5,
            'comment' => 'Kedua',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertEquals(1, Review::where('user_id', $this->user->id)->count());
    }

    public function test_review_gagal_rating_kurang_dari_1(): void
    {
        $this->createPurchase();
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 0,
        ]);
        $response->assertSessionHasErrors('rating');
    }

    public function test_review_gagal_rating_lebih_dari_5(): void
    {
        $this->createPurchase();
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 6,
        ]);
        $response->assertSessionHasErrors('rating');
    }

    public function test_review_gagal_tanpa_rating(): void
    {
        $this->createPurchase();
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'comment' => 'Tanpa rating',
        ]);
        $response->assertSessionHasErrors('rating');
    }

    public function test_review_berhasil_tanpa_comment(): void
    {
        $this->createPurchase();
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 4,
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'user_id' => $this->user->id,
            'rating' => 4,
            'comment' => null,
        ]);
    }

    public function test_guest_tidak_bisa_review(): void
    {
        $response = $this->post(route('reviews.store', $this->product), [
            'rating' => 5,
        ]);
        $response->assertRedirect(route('login'));
    }

    public function test_review_comment_max_1000_karakter(): void
    {
        $this->createPurchase();
        $response = $this->actingAs($this->user)->post(route('reviews.store', $this->product), [
            'rating' => 3,
            'comment' => str_repeat('a', 1001),
        ]);
        $response->assertSessionHasErrors('comment');
    }

    public function test_detail_produk_tampilkan_status_review(): void
    {
        $this->createPurchase();

        // Sebelum review
        $response = $this->actingAs($this->user)->get(route('products.show', $this->product));
        $response->assertViewHas('hasBought', true);
        $response->assertViewHas('hasReviewed', false);

        // Setelah review
        Review::create([
            'user_id' => $this->user->id,
            'product_id' => $this->product->id,
            'rating' => 5,
            'comment' => 'Bagus',
        ]);

        $response = $this->actingAs($this->user)->get(route('products.show', $this->product));
        $response->assertViewHas('hasBought', true);
        $response->assertViewHas('hasReviewed', true);
    }
}
