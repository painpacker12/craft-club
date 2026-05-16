<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FrontControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_returns_ok(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_category_page_returns_ok(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description'
        ]);

        $response = $this->get('/category/' . $category->slug);
        $response->assertStatus(200);
    }

    public function test_category_page_returns_404_for_invalid_slug(): void
    {
        $response = $this->get('/category/invalid-slug');
        $response->assertStatus(404);
    }


    public function test_statya_page_returns_404_for_invalid_id(): void
    {
        $response = $this->get('/statya/99999');
        $response->assertStatus(404);
    }

    public function test_booking_confirm_requires_auth(): void
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description'
        ]);

        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'master'
        ]);

        $masterClass = MasterClass::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Class',
            'description' => 'Description',
            'date' => '2025-12-31',
            'time_slot' => '9-11',
            'max_attendees' => 10,
            'price' => 1000
        ]);

        $response = $this->get('/booking/confirm/' . $masterClass->id);
        $response->assertStatus(302); // redirect to login
        $response->assertRedirect('/login');
    }

    public function test_booking_confirm_allows_authenticated_user(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified' => 1
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description'
        ]);

        $master = User::create([
            'name' => 'Master User',
            'email' => 'master@example.com',
            'password' => bcrypt('password'),
            'role' => 'master'
        ]);

        $masterClass = MasterClass::create([
            'user_id' => $master->id,
            'category_id' => $category->id,
            'title' => 'Test Class',
            'description' => 'Description',
            'date' => date('Y-m-d', strtotime('+1 month')),
            'time_slot' => '9-11',
            'max_attendees' => 10,
            'price' => 1000
        ]);

        $response = $this->actingAs($user)->get('/booking/confirm/' . $masterClass->id);
        $response->assertStatus(200);
    }
}