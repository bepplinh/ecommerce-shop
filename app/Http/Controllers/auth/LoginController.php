<?php

namespace App\Http\Controllers\auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;
        $status = Auth::attempt(['username' => $username, 'password' => $password]);
        if ($status) {
            $user = Auth::user();

            if ($user->is_admin) {
                return redirect()->route('indexAdmin')->with([
                    'toastr' => [
                        'status' => 'success',
                        'message' => 'Login Successfully'
                    ]
                ]);
            }
            return redirect()->route('home')->with([
                'toastr' => [
                    'status' => 'success',
                    'message' => 'Login Successfully'
                ]
            ]);
        }
        return redirect()->back()->with('toastr', [
            'status' => 'error',
            'message' => 'Login failed, please check your username and password',
        ]);
    }
}
