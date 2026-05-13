<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * Root route redirects to login for guests.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        // Root route redirects unauthenticated users to login
        $response->assertRedirect(route('login'));
    }
}
