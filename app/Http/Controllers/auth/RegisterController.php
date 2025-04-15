<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function registerForm() {
        return view('register');
    }

    public function register(Request $request) {
        try {
            $request->validate([
                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'string', 'min:3', 'confirmed'],
            ]);
    
            $user = new User();
            $user->username = $request->username;
            $user->password = Hash::make($request->password);
            $user->email = $request->email;
            $user->is_admin = 0; // Default to non-admin
            $user->save();
    
            return redirect()->route('login')->with('toastr', [
                'status' => 'success',
                'message' => 'User created successfully!'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Lấy lỗi đầu tiên (hoặc tất cả nếu muốn)
            $message = collect($e->validator->errors()->all())->first();
    
            return redirect()->back()->withInput()->with('toastr', [
                'status' => 'error',
                'message' => $message
            ]);
        }
    }
    
}
