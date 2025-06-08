<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function login()
    {
        return Inertia::render('Admin/Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::find(Auth::id());

            if (!$user->isAdmin()) {
                Auth::logout();
                return redirect()->route('admin.login')->withErrors([
                    'access' => 'Anda tidak memiliki akses ke halaman ini.',
                ]);
            }

            return redirect()->route('admin.dashboard');
        }

        return redirect()->back()->withErrors([
            'access' => 'Username atau password salah.',
        ]);
    }
}
