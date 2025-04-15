<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        // Tạo các category chính
        $categories = [
            'Áo Sơ Mi',
            'Quần Jean',
            'Giày Dép',
            'Áo Khoác',
            'Phụ Kiện',
            'Đầm/Váy',
        ];

        foreach ($categories as $categoryName) {
            Category::create([
                'name' => $categoryName,
                'description' => $faker->sentence,
                'parent_id' => null,  // Đây là category cấp cao nhất
            ]);
        }

        // Tạo các sub-category nếu cần
        $subCategories = [
            ['name' => 'Áo Sơ Mi Nam', 'parent_id' => 1],
            ['name' => 'Áo Sơ Mi Nữ', 'parent_id' => 1],
            ['name' => 'Quần Jean Nam', 'parent_id' => 2],
            ['name' => 'Quần Jean Nữ', 'parent_id' => 2],
            ['name' => 'Giày Thể Thao', 'parent_id' => 3],
            ['name' => 'Giày Cao Gót', 'parent_id' => 3],
            ['name' => 'Áo Khoác Nam', 'parent_id' => 4],
            ['name' => 'Áo Khoác Nữ', 'parent_id' => 4],
            ['name' => 'Dây Chuyền', 'parent_id' => 5],
            ['name' => 'Bóp Ví', 'parent_id' => 5],
            ['name' => 'Đầm Nữ', 'parent_id' => 6],
            ['name' => 'Váy Nữ', 'parent_id' => 6],
        ];

        foreach ($subCategories as $subCategory) {
            Category::create([
                'name' => $subCategory['name'],
                'description' => $faker->sentence,
                'parent_id' => $subCategory['parent_id'],
            ]);
        }
    }
}
