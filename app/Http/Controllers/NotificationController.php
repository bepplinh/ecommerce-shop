<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function notify(Request $request)
    {
        // Validate the incoming request
        $status = $request->status ?? 'info'; // success, error, warning, info
        $message = $request->message ?? 'Thông báo mặc định';

        session()->flash('toastr', ['status' => $status, 'message' => $message]);


        return back();
    }
}
