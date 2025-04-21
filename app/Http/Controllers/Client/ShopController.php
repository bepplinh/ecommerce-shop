<?php

namespace App\Http\Controllers\Client;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    public function index() {
        $products = Product::get();
        $categories = Category::get();
        $brands = Brand::get();
        $sizes = Size::get();
        $products = Product::with(['mainImage', 'category', 'brand'])->paginate(10)->withQueryString();
        // Search functionality
        if (request('search')) {
            $products = Product::where('name', 'like', '%' . request('search') . '%')
                ->orWhere('code', 'like', '%' . request('search') . '%')
                ->with(['mainImage', 'category', 'brand'])
                ->paginate(10)
                ->withQueryString();
        }
        // Sorting functionality
        switch (request('sort')) {
            case 'name_asc':
                $products = $products->sortBy('name');
                break;
            case 'name_desc':
                $products = $products->sortByDesc('name');
                break;
            case 'price_asc':
                $products = $products->sortBy('price');
                break;
            case 'price_desc':
                $products = $products->sortByDesc('price');
                break;
            case 'newest':
                $products = $products->sortByDesc('created_at');
                break;
            case 'oldest':
                $products = $products->sortBy('created_at');
                break;
            default:
                $products = Product::latest()->with(['mainImage', 'category', 'brand'])->paginate(10)->withQueryString();
        }
        // Filter by category
        if (request('category')) {
            $products = $products->where('category_id', request('category'));
        }
        // Filter by brand
        if (request('brand')) {
            $products = $products->where('brand_id', request('brand'));
        }
        // Filter by size
        if (request('size')) {
            $products = $products->whereHas('sizes', function ($query) {
                $query->where('size_id', request('size'));
            });
        }
        // Filter by price range
        if (request('price_min') && request('price_max')) {
            $products = $products->whereBetween('price', [request('price_min'), request('price_max')]);
        } elseif (request('price_min')) {
            $products = $products->where('price', '>=', request('price_min'));
        } elseif (request('price_max')) {
            $products = $products->where('price', '<=', request('price_max'));
        }
        // Filter by status
        if (request('status')) {
            $products = $products->where('status', request('status'));
        }
        // Filter by stock
        if (request('stock')) {
            $products = $products->whereHas('sizes', function ($query) {
                $query->where('stock', '>', 0);
            });
        }
        // Filter by rating
        if (request('rating')) {
            $products = $products->whereHas('reviews', function ($query) {
                $query->where('rating', '>=', request('rating'));
            });
        }

        return view('client.shop', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'sizes' => $sizes,
            'title' => 'Shop',
        ]);
    }
}
