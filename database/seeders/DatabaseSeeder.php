<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Добавляем несколько видов творчества из меню
        $categories = [
            ['name' => 'Архитектурное моделирование', 'slug' => 'arhitekturnoe-modelirovanie'],
            ['name' => 'Кулинария', 'slug' => 'kulinariya'],
            ['name' => 'Резьба по дереву', 'slug' => 'rezba-po-derevu'],
        ];
        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'description' => 'Здесь будет описание для ' . $cat['name'],
                'image' => 'driver1.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Добавляем одного ведущего мастер-класса
        DB::table('users')->insert([
            'name' => 'Иванова Ольга Ивановна',
            'email' => 'master@example.com',
            'phone' => '89123456765',
            'password' => Hash::make('password'),
            'role' => 'master',
            'photo' => 'driver-page.png',
            'about' => 'Опытный ведущий с многолетним стажем.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}