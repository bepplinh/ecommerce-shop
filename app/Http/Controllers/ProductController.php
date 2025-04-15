public function index(Request $request)
{
    $query = Product::query();
    
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('code', 'like', "%{$search}%");
        });
    }
    
    $products = $query->paginate(10)->withQueryString();
    
    return view('product.listProduct', compact('products'));
}

public function edit($id)
{
    $product = Product::findOrFail($id);
    $sizes = Size::all();
    $categories = Category::all();
    $brands = Brand::all();
    
    return view('product.editProduct', compact('product', 'sizes', 'categories', 'brands'));
}

public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    // Clean price value before validation
    $request->merge([
        'price' => str_replace([',', '.'], '', $request->price)
    ]);
    
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:products,code,' . $id,
        'price' => 'required|numeric|min:0',
        'size' => 'required',
        'description' => 'nullable|string',
        'stock' => 'required|integer|min:0',
        'status' => 'required|in:active,inactive',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    $data = $request->except('image');
    
    if ($request->hasFile('image')) {
        // Delete old image
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }
        
        $imagePath = $request->file('image')->store('products', 'public');
        $data['image'] = $imagePath;
    }

    $product->update($data);
    
    return redirect()->route('products.index')
        ->with('success', 'Sản phẩm đã được cập nhật thành công.');
}