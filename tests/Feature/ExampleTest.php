<?php

namespace Tests\Feature;

use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('posts.index'));
    }

    public function test_admin_routes_redirect_guests_to_google_login(): void
    {
        config(['admin.emails' => ['admin@example.com']]);

        $response = $this->get(route('posts.create'));

        $response->assertRedirect(route('login.google'));
    }

    public function test_allowed_admin_email_can_access_admin_routes(): void
    {
        config(['admin.emails' => ['admin@example.com']]);

        $user = new User([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('posts.create'));

        $response->assertOk();
    }
}
