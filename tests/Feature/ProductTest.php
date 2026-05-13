<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->user = User::factory()->create(['role' => 'user']);
    }

    public function test_halaman_produk_dapat_ditampilkan(): void
    {
        Product::factory()->count(3)->create();
        $response = $this->actingAs($this->user)->get(route('products.index'));
        $response->assertStatus(200);
        $response->assertViewIs('index');
        $response->assertViewHas('products');
    }

    public function test_guest_tidak_bisa_akses_produk(): void
    {
        $response = $this->get(route('products.index'));
        $response->assertRedirect(route('login'));
    }

    public function test_search_berdasarkan_nama(): void
    {
        Product::factory()->create(['name' => 'RTX 4090 GPU']);
        Product::factory()->create(['name' => 'Ryzen 9 CPU']);
        $response = $this->actingAs($this->user)->get(route('products.index', ['search' => 'RTX']));
        $response->assertStatus(200);
        $response->assertViewHas('products', fn($p) => $p->count() === 1);
    }

    public function test_search_berdasarkan_deskripsi(): void
    {
        Product::factory()->create(['name' => 'A', 'description' => 'Kartu grafis terbaik']);
        Product::factory()->create(['name' => 'B', 'description' => 'Prosesor cepat']);
        $response = $this->actingAs($this->user)->get(route('products.index', ['search' => 'grafis']));
        $response->assertViewHas('products', fn($p) => $p->count() === 1);
    }

    public function test_search_kosong_tampilkan_semua(): void
    {
        Product::factory()->count(5)->create();
        $response = $this->actingAs($this->user)->get(route('products.index', ['search' => '']));
        $response->assertViewHas('products', fn($p) => $p->count() === 5);
    }

    public function test_detail_produk(): void
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->get(route('products.show', $product));
        $response->assertStatus(200);
        $response->assertViewIs('show');
        $response->assertViewHas('hasBought', false);
        $response->assertViewHas('hasReviewed', false);
    }

    public function test_admin_akses_create(): void
    {
        $response = $this->actingAs($this->admin)->get(route('products.create'));
        $response->assertStatus(200);
    }

    public function test_user_tidak_bisa_create(): void
    {
        $response = $this->actingAs($this->user)->get(route('products.create'));
        $response->assertStatus(403);
    }

    public function test_admin_store_produk(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'RTX 5090', 'category' => 'vga',
            'description' => 'Terbaru', 'price' => 35000000, 'stock' => 10,
        ]);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['name' => 'RTX 5090']);
    }

    public function test_admin_store_dengan_gambar(): void
    {
        Storage::fake('public');
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'GPU', 'category' => 'vga', 'description' => 'Desc',
            'price' => 1000, 'stock' => 5,
            'images' => [
                UploadedFile::fake()->create('g1.jpg', 100, 'image/jpeg'),
                UploadedFile::fake()->create('g2.png', 100, 'image/png'),
            ],
        ]);
        $response->assertRedirect(route('products.index'));
        $this->assertCount(2, Product::first()->images);
    }

    public function test_user_tidak_bisa_store(): void
    {
        $response = $this->actingAs($this->user)->post(route('products.store'), [
            'name' => 'X', 'category' => 'vga', 'description' => 'X', 'price' => 1, 'stock' => 1,
        ]);
        $response->assertStatus(403);
    }

    public function test_store_gagal_validasi(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), []);
        $response->assertSessionHasErrors(['name', 'category', 'description', 'price', 'stock']);
    }

    public function test_store_kategori_invalid(): void
    {
        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'X', 'category' => 'invalid', 'description' => 'X', 'price' => 1, 'stock' => 1,
        ]);
        $response->assertSessionHasErrors('category');
    }

    public function test_admin_update_produk(): void
    {
        $product = Product::factory()->create(['name' => 'Old']);
        $response = $this->actingAs($this->admin)->put(route('products.update', $product), [
            'name' => 'New', 'category' => 'cpu', 'description' => 'Upd', 'price' => 500, 'stock' => 20,
        ]);
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('products', ['id' => $product->id, 'name' => 'New']);
    }

    public function test_user_tidak_bisa_update(): void
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->put(route('products.update', $product), [
            'name' => 'Hack', 'category' => 'cpu', 'description' => 'X', 'price' => 1, 'stock' => 1,
        ]);
        $response->assertStatus(403);
    }

    public function test_admin_hapus_produk(): void
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->admin)->delete(route('products.destroy', $product));
        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_user_tidak_bisa_hapus(): void
    {
        $product = Product::factory()->create();
        $response = $this->actingAs($this->user)->delete(route('products.destroy', $product));
        $response->assertStatus(403);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_hapus_produk_beserta_gambar(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('products/img1.jpg', 'fake');
        $product = Product::factory()->create(['image' => 'products/img1.jpg', 'images' => ['products/img1.jpg']]);
        $this->actingAs($this->admin)->delete(route('products.destroy', $product));
        Storage::disk('public')->assertMissing('products/img1.jpg');
    }

    public function test_semua_kategori_valid(): void
    {
        $cats = ['cpu','motherboard','vga','ram','storage','psu','casing','cooling','aksesoris'];
        foreach ($cats as $c) {
            $this->actingAs($this->admin)->post(route('products.store'), [
                'name' => "P $c", 'category' => $c, 'description' => 'D', 'price' => 100, 'stock' => 1,
            ]);
            $this->assertDatabaseHas('products', ['category' => $c]);
        }
    }
}
