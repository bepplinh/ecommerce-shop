<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Size; 
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create('vi_VN');

        $products = [
            ['name' => 'Áo Sơ Mi Nam', 'category_id' => 1, 'brand_id' => 1],
            ['name' => 'Áo Sơ Mi Nữ', 'category_id' => 1, 'brand_id' => 2],
            ['name' => 'Quần Jean Nam', 'category_id' => 2, 'brand_id' => 3],
            ['name' => 'Quần Jean Nữ', 'category_id' => 2, 'brand_id' => 4],
            ['name' => 'Giày Thể Thao', 'category_id' => 3, 'brand_id' => 5],
            ['name' => 'Giày Cao Gót', 'category_id' => 3, 'brand_id' => 6],
            ['name' => 'Áo Khoác Nam', 'category_id' => 4, 'brand_id' => 7],
            ['name' => 'Áo Khoác Nữ', 'category_id' => 4, 'brand_id' => 8],
            ['name' => 'Dây Chuyền Nữ', 'category_id' => 5, 'brand_id' => 2],
            ['name' => 'Bóp Ví Nam', 'category_id' => 5, 'brand_id' => 3],
            ['name' => 'Đầm Nữ', 'category_id' => 6, 'brand_id' => 1],
            ['name' => 'Váy Nữ', 'category_id' => 6, 'brand_id' => 4],
        ];

        // Lấy tất cả các size từ bảng sizes
        $sizes = Size::all();

        foreach ($products as $product) {
            // Lấy một size ngẫu nhiên từ bảng sizes
            $randomSize = $sizes->random();

            Product::create([
                'name' => $product['name'],
                'code' => strtoupper(Str::random(8)),
                'description' => $faker->sentence,
                'price' => rand(100000, 2000000),
                'category_id' => $product['category_id'],
                'brand_id' => $product['brand_id'],
                'status' => collect(['active', 'inactive'])->random(),
            ]);
        }
    }
}
