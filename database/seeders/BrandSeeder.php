<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run()
    {
        $brands = [
            'Nike',
            'Adidas',
            'Zara',
            'H&M',
            'Uniqlo',
            'Puma',
            'Converse',
            'Levi\'s',
        ];

        foreach ($brands as $brandName) {
            Brand::create([
                'name' => $brandName,
                'description' => 'Thương hiệu nổi tiếng chuyên cung cấp các sản phẩm thời trang chất lượng cao.',
                'image' => null, // nếu muốn, có thể thay bằng link ảnh hoặc để null
            ]);
        }
    }
}
