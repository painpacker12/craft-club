<?php

namespace Tests\Unit\Middleware;

use Tests\TestCase;
use App\Models\User;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->middleware = new CheckRole();
    }

    public function test_middleware_allows_user_with_correct_role(): void
    {
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'admin');

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_middleware_denies_user_with_wrong_role(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn () => $user);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'admin');

        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_middleware_denies_guest(): void
    {
        $request = Request::create('/admin', 'GET');
        $request->setUserResolver(fn () => null);

        $response = $this->middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'admin');

        $this->assertEquals(403, $response->getStatusCode());
    }
}