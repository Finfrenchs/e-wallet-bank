<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminUser;
use Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->back()
            ->with('error', 'Invalid credentials.')
            ->withInput();
    }

    //logout
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('admin.auth.index');
    }
}
