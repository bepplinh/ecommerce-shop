<?php

namespace App\Http\Controllers\Brand;

use Illuminate\Support\Facades\Storage;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('brand.indexBrand')->with([
            'brands' => $brands,
            'title' => 'Brand List',
            'heading' => 'Brand List',
        ]);
    }

    public function create()
    {
        return view('brand.addBrand')->with([
            'title' => 'Create Brand',
            'heading' => 'Add New Brand',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('brands', 'public');
        }

        $brand = new Brand();
        $brand->name = $request->name;
        $brand->description = $request->description;
        $brand->image = $imagePath;
        $brand->save();

        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'Brand created successfully!'
        ]);
    }

    public function show(Brand $brand)
    {
        //
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('brand.editBrand')->with([
            'brand' => $brand,
            'title' => 'Edit Brand',
            'heading' => 'Edit Brand',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|max:2048',
        ]);

        $brand = Brand::findOrFail($id);
        $brand->name = $request->name;
        $brand->description = $request->description;

        if ($request->hasFile('image')) {
            // Xoá ảnh cũ nếu có
            if ($brand->image && Storage::disk('public')->exists($brand->image)) {
                Storage::disk('public')->delete($brand->image);
            }

            // Lưu ảnh mới vào thư mục public/storage
            $imagePath = $request->file('image')->store('brands', 'public');
            $brand->image = $imagePath;
        }

        $brand->save();
        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'Brand updated successfully!'
        ]);
    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image && Storage::disk('public')->exists($brand->image)) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'Brand deleted successfully!'
        ]);
    }
}
