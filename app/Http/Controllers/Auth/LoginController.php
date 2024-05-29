<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            $response = Http::post('https://hris.truest.co.id/api/v1/login', $credentials);

            if ($response->ok()) {
                $userData = $response->json()['data'];
                Session::put('userData', $userData);
                return redirect()->route('dashboard.index');
            } else {
                throw new \Exception('Login failed. Invalid credentials.');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])
                ->withInput($request->only('email')); // Menambahkan kembali input email ke dalam form
        }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('login');
    }
}
