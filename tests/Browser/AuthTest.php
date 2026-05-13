<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function test_user_can_register()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->assertSee('Login / Sign up')
                    ->type('name', 'John Doe')
                    ->type('email', 'john@example.com')
                    ->type('password', 'password123')
                    ->type('password_confirmation', 'password123')
                    ->press('Create Account')
                    ->assertPathIs('/products')
                    ->assertAuthenticated();
        });
    }

    public function test_user_can_login()
    {
        $user = User::factory()->create([
            'email' => 'jane@example.com',
            // Gunakan password default dari factory (biasanya 'password')
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->assertSee('Login / Sign up')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Log In')
                    ->waitForLocation('/products', 5)
                    ->assertPathIs('/products')
                    ->assertAuthenticatedAs($user);
        });
    }

    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                    ->visit('/')
                    ->visit('/logout') // Memanggil route logout secara langsung untuk menghindari flaky UI button submit di Windows
                    ->assertPathIs('/login')
                    ->assertGuest();
        });
    }
}
