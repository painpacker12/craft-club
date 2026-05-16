<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Category;
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
        $category = Category::factory()->create();
        $response = $this->get('/category/' . $category->slug);
        $response->assertStatus(200);
    }
}