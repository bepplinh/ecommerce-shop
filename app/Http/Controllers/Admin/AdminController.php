<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $title = 'Admin Dashboard';
        $heading = 'Chào mừng Admin';

        return view('admin.indexAdmin', compact('title', 'heading'));
    }
}
