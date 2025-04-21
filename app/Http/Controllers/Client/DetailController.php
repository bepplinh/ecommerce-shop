<?php

namespace App\Http\Controllers\Client;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DetailController extends Controller
{
    public function showProductDetail($id)
    {
        $product = Product::with(['images', 'sizes'])->findOrFail($id);
        return view('client.detail', compact('product'));
    }
}
