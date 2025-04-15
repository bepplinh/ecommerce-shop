<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function showCreateUser() {
        return view('admin.createUser')->with([
            'title' => 'Create User',
            'heading' => 'Create User',
        ]);
    }
    public function createUser(Request $request) {
        $request->validate([
            'username' => ['required','string','max:255','unique:users'],
            'password' => ['required','string','min:3','confirmed'],
            'is_admin' => ['required','boolean','integer'],
        ]);

        $user = new User;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->is_admin = $request->is_admin;
        $user->save();

        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'User created successfully!'
        ]);
    }

    public function showEditUser($id) {
        $user = User::findOrFail($id);
        return view('admin.editUser')->with([
            'title' => 'Edit User',
            'heading' => 'Edit User',
            'user' => $user,
        ]);
    }

    public function editUser(Request $request, $id) {
        $request->validate([
            'username' => ['required','string','max:255','unique:users,username,'.$id],
            'password' => ['nullable','string','min:3','confirmed'],
            'is_admin' => ['required','boolean','integer'],
        ]);

        $user = User::findOrFail($id);
        $user->username = $request->username;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->is_admin = $request->is_admin;
        $user->save();

        return redirect()->back()->with('toastr', [
            'status' => 'success',
            'message' => 'User updated successfully!'
        ]);
    }
}
