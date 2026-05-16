<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\MasterClass;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterClassTest extends TestCase
{
    use RefreshDatabase;

    public function test_master_class_can_be_created(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $masterClass = MasterClass::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Test Master Class',
            'description' => 'Test description',
            'date' => '2025-12-31',
            'time_slot' => '9-11',
            'price' => 1000,
            'max_attendees' => 10
        ]);

        $this->assertDatabaseHas('master_classes', ['title' => 'Test Master Class']);
    }
}