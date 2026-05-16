<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::create([
            'name' => 'Master User',
            'email' => 'master@example.com',
            'password' => bcrypt('password'),
            'role' => 'master',
            'email_verified' => 1
        ]);
        $this->category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test description'
        ]);
    }

    public function test_dashboard_requires_authentication(): void
    {
        $response = $this->get('/master/dashboard');
        $response->assertStatus(302); // redirect to login
        $response->assertRedirect('/login');
    }

    public function test_dashboard_allows_master(): void
    {
        $response = $this->actingAs($this->admin)->get('/master/dashboard');
        $response->assertStatus(200);
    }

    public function test_create_class_form_allows_master(): void
    {
        $response = $this->actingAs($this->admin)->get('/master/classes/create');
        $response->assertStatus(200);
    }

    public function test_store_class_creates_master_class(): void
    {
        $response = $this->actingAs($this->admin)->post('/master/classes', [
            'category_id' => $this->category->id,
            'title' => 'New Master Class',
            'description' => 'Description of master class',
            'date' => '2025-12-31',
            'time_slot' => '9-11',
            'max_attendees' => 10,
            'price' => 1500
        ]);

        $response->assertStatus(302); // redirect
        $this->assertDatabaseHas('master_classes', ['title' => 'New Master Class']);
    }

    public function test_store_class_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->post('/master/classes', []);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['category_id', 'title', 'description', 'date', 'time_slot', 'max_attendees', 'price']);
    }

    public function test_store_class_prevents_duplicate_time_slot(): void
    {
        // Create first master class
        MasterClass::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'First Class',
            'description' => 'Description',
            'date' => '2025-12-31',
            'time_slot' => '9-11',
            'max_attendees' => 10,
            'price' => 1000
        ]);

        // Try to create second at same time
        $response = $this->actingAs($this->admin)->post('/master/classes', [
            'category_id' => $this->category->id,
            'title' => 'Second Class',
            'description' => 'Description',
            'date' => '2025-12-31',
            'time_slot' => '9-11',
            'max_attendees' => 10,
            'price' => 1000
        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error', 'У вас уже запланирован мастер-класс на это время');
    }

    public function test_update_class_updates_description_and_price(): void
    {
        $masterClass = MasterClass::create([
            'user_id' => $this->admin->id,
            'category_id' => $this->category->id,
            'title' => 'Original Title',
            'description' => 'Original description',
            'date' => '2025-12-31',
            'time_slot' => '9-11',
            'max_attendees' => 10,
            'price' => 1000
        ]);

        $response = $this->actingAs($this->admin)->put("/master/classes/{$masterClass->id}", [
            'description' => 'Updated description',
            'price' => 2000
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('master_classes', [
            'id' => $masterClass->id,
            'description' => 'Updated description',
            'price' => 2000
        ]);
    }

    public function test_non_master_cannot_access_master_pages(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
            'email_verified' => 1
        ]);

        $response = $this->actingAs($user)->get('/master/dashboard');
        $response->assertStatus(403);

        $response = $this->actingAs($user)->get('/master/classes/create');
        $response->assertStatus(403);
    }
}