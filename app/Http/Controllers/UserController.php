<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserController extends Controller
{
    public function login()
    {
        $store = Store::with([
            'advantages',
            'certificates' => function ($query) {
                $query->limit(5);
            },
            'social_links',
        ])->first();

        return Inertia::render('Auth/Login', [
            'store' => $store,
        ]);
    }

    public function loginProcess(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $redirectUrl = $request->input('redirect');

            if ($redirectUrl) {
                return redirect()->to($redirectUrl)->with([
                    'success' => 'Berhasil masuk.',
                ]);
            }

            return redirect()->route('home')->with([
                'success' => 'Berhasil masuk.',
            ]);
        }

        return redirect()->back()->withErrors([
            'access' => 'Username atau password salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home')->with([
            'success' => 'Berhasil keluar.',
        ]);
    }
}
