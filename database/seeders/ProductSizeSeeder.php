<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Size;

class ProductSizeSeeder extends Seeder
{
    public function run()
    {
        $products = Product::all();
        $sizes = Size::all();

        // Gán size ngẫu nhiên và stock cho mỗi product
        $products->each(function ($product) use ($sizes) {
            // Lấy 2–4 size ngẫu nhiên
            $selectedSizes = $sizes->random(rand(2, 4));

            // Gắn với số lượng stock ngẫu nhiên cho từng size
            $syncData = [];

            foreach ($selectedSizes as $size) {
                $syncData[$size->id] = [
                    'stock' => rand(10, 100)
                ];
            }

            $product->sizes()->sync($syncData);
        });
    }
}
