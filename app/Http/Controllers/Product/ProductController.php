<?php

namespace App\Http\Controllers\Product;

use App\Models\Size;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use App\Models\ProductImage;
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

        $products = $query->with('mainImage')->paginate(10)->withQueryString();
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
            'images' => ['required', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
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
        $product->save();

        $slug = Str::slug($product->name);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $index => $image) {
                $extension = $image->getClientOriginalExtension();
                $imageName = "{$slug}-" . ($index + 1) . "." . $extension;
                $imagePath = $image->storeAs("products/{$slug}", $imageName, 'public');

                $product->images()->create([
                    'image_path' => 'storage/' . $imagePath,
                    'is_main' => $index === 0, //ảnh đầu tiên là ảnh đại diện
                ]);
            }
        }

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
        return view('client.detail')->with([
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

    // Lấy dữ liệu không liên quan đến ảnh
    $data = $request->except(['images', 'sizes', 'replace_images', 'deleted_images', 'main_image_id']);

    // Xử lý ảnh thay thế
    if ($request->has('replace_images')) {
        foreach ($request->replace_images as $imageId => $newImage) {
            $productImage = ProductImage::findOrFail($imageId);

            // Nếu ảnh cũ là ảnh chính thì gán cờ để sau xử lý
            $wasMain = $productImage->is_main;

            // Xóa ảnh cũ nếu tồn tại
            $oldImagePath = public_path('storage/' . $productImage->image_path);
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Xóa ảnh trong DB
            $productImage->delete();

            // Upload ảnh mới thay thế
            $extension = $newImage->getClientOriginalExtension();
            $imageName = Str::slug($product->name) . '-' . uniqid() . '.' . $extension;
            $imagePath = $newImage->storeAs('products/' . Str::slug($product->name), $imageName, 'public');

            // Lưu ảnh mới vào DB
            $product->images()->create([
                'image_path' => 'storage/' . $imagePath,
                'is_main' => $wasMain, // Nếu ảnh cũ là ảnh chính, thì ảnh mới cũng là ảnh chính
            ]);
        }
    }

    // Xử lý ảnh đã xóa
    if ($request->has('deleted_images')) {
        $deletedImageIds = array_filter(explode(',', $request->deleted_images));
        if (!empty($deletedImageIds)) {
            $deletedImages = ProductImage::whereIn('id', $deletedImageIds)->get();
            foreach ($deletedImages as $deletedImage) {
                $oldImagePath = public_path('storage/' . $deletedImage->image_path);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $deletedImage->delete();
            }
        }
    }

    // Xử lý ảnh mới
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $index => $image) {
            $extension = $image->getClientOriginalExtension();
            $imageName = Str::slug($product->name) . '-' . uniqid() . '.' . $extension;
            $imagePath = $image->storeAs('products/' . Str::slug($product->name), $imageName, 'public');
        
            // Lưu tất cả các ảnh vào database
            $product->images()->create([
                'image_path' => 'storage/' . $imagePath,
                'is_main' => false, // Tạm thời là false, ảnh chính xử lý sau
            ]);
        }
    }

    // Cập nhật ảnh chính (main_image_id)
    if ($request->filled('main_image_id')) {
        $mainImageId = $request->input('main_image_id');

        // Đảm bảo tất cả ảnh của sản phẩm có is_main = false
        $product->images()->update(['is_main' => false]);

        // Đặt ảnh được chọn là ảnh chính
        ProductImage::where('id', $mainImageId)->update(['is_main' => true]);
    }

    // Cập nhật thông tin sản phẩm
    $product->update($data);

    // Cập nhật size và tồn kho
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

    public function deleteImage($id)
    {
        $image = ProductImage::findOrFail($id);
        $oldImagePath = public_path($image->image_path);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
        $image->delete();
        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'Image deleted successfully',
        ]);
    }
}
