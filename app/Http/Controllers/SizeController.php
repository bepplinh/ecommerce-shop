public function index(Request $request)
{
    $sort = $request->query('sort', 'asc');
    $sizes = Size::orderBy('name', $sort)->get();
    
    return view('product.addSize', [
        'sizes' => $sizes,
        'title' => 'Manage Sizes',
        'heading' => 'Size Management'
    ]);
}