<?php

namespace App\Http\Controllers\Product;

use App\Models\Size;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SizeController extends Controller
{
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

    public function store()
    {
        $request = request();
        $request->validate([
            'name' => ['required', 'string', 'max:10'],
        ]);

        $size = new Size;
        $size->name = $request->name;
        $size->save();

        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'Size added successfully',
        ]);
    }

    public function update($id)
    {
        $request = request();
        $request->validate([
            'name' => ['required', 'string', 'max:10'],
        ]);

        $size = Size::find($id);
        if ($size) {
            $size->name = $request->name;
            $size->save();
            return redirect()->back()->with('toastr', [
                'status' => 'success',
                'message' => 'Size updated successfully',
            ]);
        } else {
            return redirect()->back()->with('toastr', [
                'status' => 'error',
                'message' => 'Size not found',
            ]);
        }
    }
    public function delete($id)
    {
        $size = Size::find($id);
        if ($size) {
            $size->delete();
            return redirect()->back()->with('toastr', [
                'status' => 'success',
                'message' => 'Size deleted successfully',
            ]);
        } else {
            return redirect()->back()->with('toastr', [
                'status' => 'error',
                'message' => 'Size not found',
            ]);
        }
    }
}
