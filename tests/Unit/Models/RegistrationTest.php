<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterClass;
use App\Models\Registration;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_can_be_created(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test'
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

        $registration = Registration::create([
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed'
        ]);

        $this->assertDatabaseHas('registrations', [
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed'
        ]);
    }

    public function test_registration_belongs_to_user(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'Test'
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

        $registration = Registration::create([
            'user_id' => $user->id,
            'master_class_id' => $masterClass->id,
            'status' => 'confirmed'
        ]);

        $this->assertEquals($user->id, $registration->user->id);
    }
}