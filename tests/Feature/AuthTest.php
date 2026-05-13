<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Whitebox Testing - AuthController
 * 
 * Menguji seluruh alur autentikasi:
 * - Register (validasi, pembuatan user, auto-login)
 * - Login (kredensial valid/invalid, redirect)
 * - Logout (invalidasi session)
 * - Guard (guest/auth middleware)
 */
class AuthTest extends TestCase
{
    use RefreshDatabase;

    // ============================================================
    // REGISTER TESTS (Pengujian Registrasi)
    // ============================================================

    /** @test */
    public function test_halaman_register_dapat_ditampilkan(): void
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.register');
    }

    public function test_user_yang_sudah_login_diredirect_dari_halaman_register(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get(route('register'));
        // Guest middleware redirect ke '/', lalu root chain ke products
        $response->assertRedirect('/');
    }

    /** @test */
    public function test_register_berhasil_dengan_data_valid(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
            'role' => 'user',
        ]);
        $this->assertAuthenticated();
    }

    /** @test */
    public function test_register_berhasil_sebagai_admin(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertDatabaseHas('users', [
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function test_register_gagal_tanpa_nama(): void
    {
        $response = $this->post(route('register.submit'), [
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertSessionHasErrors('name');
        $this->assertGuest();
    }

    /** @test */
    public function test_register_gagal_tanpa_email(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_register_gagal_dengan_email_duplikat(): void
    {
        User::factory()->create(['email' => 'existing@example.com', 'role' => 'user']);

        $response = $this->post(route('register.submit'), [
            'name' => 'Test',
            'email' => 'existing@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_register_gagal_password_kurang_dari_6_karakter(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => '12345',
            'password_confirmation' => '12345',
            'role' => 'user',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function test_register_gagal_password_tidak_cocok(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'differentpassword',
            'role' => 'user',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function test_register_gagal_role_tidak_valid(): void
    {
        $response = $this->post(route('register.submit'), [
            'name' => 'Test',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'superadmin',
        ]);

        $response->assertSessionHasErrors('role');
    }

    // ============================================================
    // LOGIN TESTS
    // ============================================================

    /** @test */
    public function test_halaman_login_dapat_ditampilkan(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertViewIs('auth.login');
    }

    public function test_user_yang_sudah_login_diredirect_dari_halaman_login(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get(route('login'));
        // Guest middleware redirect ke '/'
        $response->assertRedirect('/');
    }

    /** @test */
    public function test_login_berhasil_dengan_kredensial_valid(): void
    {
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'user@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('products.index'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
        ]);

        $response = $this->post(route('login.submit'), [
            'email' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function test_login_gagal_dengan_email_tidak_terdaftar(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'notexist@example.com',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function test_login_gagal_tanpa_email(): void
    {
        $response = $this->post(route('login.submit'), [
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_login_gagal_tanpa_password(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'user@example.com',
        ]);

        $response->assertSessionHasErrors('password');
    }

    /** @test */
    public function test_login_gagal_email_format_tidak_valid(): void
    {
        $response = $this->post(route('login.submit'), [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertSessionHasErrors('email');
    }

    // ============================================================
    // LOGOUT TESTS
    // ============================================================

    /** @test */
    public function test_logout_berhasil(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        
        $response = $this->actingAs($user)->post(route('logout'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    // ============================================================
    // ROOT ROUTE TEST
    // ============================================================

    /** @test */
    public function test_root_redirect_ke_login_jika_guest(): void
    {
        $response = $this->get('/');
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function test_root_redirect_ke_products_jika_authenticated(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect(route('products.index'));
    }
}
