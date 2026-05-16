<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_returns_ok(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_register_page_returns_ok(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_user_can_register(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'phone' => '+79991234567'
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role' => 'user',
            'email_verified' => 1
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(302);
        $this->assertAuthenticated();
    }

    public function test_login_with_wrong_credentials_returns_error(): void
    {
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email']);
    }

    public function test_login_with_unverified_email_returns_error(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'unverified@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified' => 0
        ]);

        $response = $this->post('/login', [
            'email' => 'unverified@example.com',
            'password' => 'password'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
    }

    public function test_logout_clears_session(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified' => 1
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');
        $response->assertStatus(302);
        $this->assertGuest();
    }
}