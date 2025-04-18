<?php

namespace App\Http\Controllers\Product;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
        }

        // Sorting functionality
        switch ($request->sort) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(10)->withQueryString();
        return view('product.listProduct')->with([
            'products' => $products,
            'title' => 'Product List',
            'heading' => 'Product List',
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        return view('product.createProduct')->with([
            'categories' => $categories,
            'brands' => $brands,
            'sizes' => $sizes,
            'title' => 'Create Product',
            'heading' => 'Add New Product',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:products,code'],
            'description' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string'],
            'status' => ['required', 'in:active,inactive'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'sizes' => ['required', 'array'],
            'sizes.*.size_id' => ['required', 'exists:sizes,id'],
            'sizes.*.stock' => ['required', 'integer', 'min:0'],
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->price = (int) str_replace(['.', ','], '', $request->price);
        $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = 'storage/' . $imagePath;
        }

        $product->save();

        // Gán các size và số lượng
        foreach ($request->sizes as $size) {
            $product->sizes()->attach($size['size_id'], ['stock' => $size['stock']]);
        }

        return redirect()->route('products.create')->with('toastr', [
            'status' => 'success',
            'message' => 'Create Product Successfully!',
        ]);
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('product.showProduct')->with([
            'product' => $product,
            'title' => 'Product Details',
            'heading' => 'Product Details',
        ]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $sizes = Size::all();
        $categories = Category::all();
        $brands = Brand::all();

        return view('product.editProduct')->with([
            'product' => $product,
            'sizes' => $sizes,
            'categories' => $categories,
            'brands' => $brands,
            'title' => 'Edit Product',
            'heading' => 'Edit Product',
        ]);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);

        $data = $request->except(['image', 'sizes']);

        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldImagePath = str_replace('storage/', '', $product->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $imagePath;
        }

        $product->update($data);

        // Xử lý cập nhật size và stock
        $sizes = $request->input('sizes', []);
        $syncData = [];

        foreach ($sizes as $size) {
            if (isset($size['id']) && isset($size['stock'])) {
                $syncData[$size['id']] = ['stock' => $size['stock']];
            }
        }

        $product->sizes()->sync($syncData);

        return redirect()->route('products.index')->with('toastr', [
            'status' => 'success',
            'message' => 'Product updated successfully',
        ]);
    }

    public function destroy(Product $product)
    {
        $product = Product::findOrFail($product->id);
        $product->delete();
        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'Product deleted successfully',
        ]);
    }
}
