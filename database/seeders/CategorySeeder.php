<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => '音楽', 'slug' => 'music'],
            ['name' => 'スポーツ', 'slug' => 'sports'],
            ['name' => 'ゲーム', 'slug' => 'games'],
            ['name' => '学問・教育', 'slug' => 'education'],
            ['name' => '趣味・生活', 'slug' => 'lifestyle'],
            ['name' => '地域', 'slug' => 'local'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
