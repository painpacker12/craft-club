<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_middleware_allows_user_with_correct_role(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $this->actingAs($user);

        $response = $this->get('/master/dashboard');
        $response->assertStatus(200);
    }

    public function test_middleware_denies_user_with_wrong_role(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $this->actingAs($user);

        $response = $this->get('/master/dashboard');
        $response->assertStatus(403);
    }

    public function test_middleware_denies_guest(): void
    {
        $response = $this->get('/master/dashboard');
        $response->assertStatus(302); // redirect to login
    }
}