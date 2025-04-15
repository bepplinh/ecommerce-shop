<?php

namespace App\Http\Controllers\Product;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            });
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
            'stock' => ['required', 'integer'],
            'size_id' => ['required', 'exists:sizes,id'],
            'status' => ['required', 'in:active,inactive'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
        ]);

        $product = new Product;

        $product->name = $request->name;
        $product->code = $request->code;
        $product->description = $request->description;
        $product->price = (int) str_replace('.', '', str_replace(',', '', $request->price));
        $product->stock = $request->stock;
        $product->size_id = $request->size_id;
        $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;


        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->image = 'storage/' . $imagePath;
        }

        $product->save();

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

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->merge([
            'price' => str_replace([',', '.'], '', $request->price)
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code,' . $id,
            'price' => 'required|numeric|min:0',
            'size_id' => 'required|exists:sizes,id',
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($product->image) {
                $oldImagePath = str_replace('storage/', '', $product->image);
                Storage::disk('public')->delete($oldImagePath);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $data['image'] = 'storage/' . $imagePath;
        }

        $product->update($data);

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
