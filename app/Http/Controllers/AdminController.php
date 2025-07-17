<?php

namespace App\Http\Controllers;

use App\Models\Product;
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

            // Create access_token for API
            $accessToken = $user->createToken('authToken')->plainTextToken;

            return redirect()->route('admin.dashboard')
                ->with([
                    'success' => 'Berhasil masuk sebagai admin.',
                    'access_token' => $accessToken,
                ]);
        }

        return redirect()->back()->withErrors([
            'access' => 'Username atau password salah.',
        ]);
    }

    public function index()
    {
        return redirect()->route('admin.dashboard');
    }

    public function dashboard()
    {
        $productCount = Product::count();
        $userCount = User::count();

        return Inertia::render(
            'Admin/Dashboard',
            [
                'productCount' => $productCount,
                'userCount' => $userCount,
            ]
        );
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login')->with('success', 'Berhasil keluar.');
    }
}
