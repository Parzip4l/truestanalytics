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
                $responseData = $response->json();
                $userData = $responseData['data'];
                $token = $responseData['token'];

                // Simpan token dalam userData
                $userData['token'] = $token;

                // Simpan userData dalam sesi
                Session::put('userData', $userData);

                // Fetch user profile using the token
                $profileResponse = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $token,
                ])->get('https://hris.truest.co.id/api/v1/profile/' . $userData['name']);

                if ($profileResponse->ok()) {
                    $userProfile = $profileResponse->json()['data'];
                    // Store userProfile in session if needed
                    Session::put('userProfile', $userProfile);
                
                } else {
                    throw new \Exception('Failed to fetch user profile.');
                }

                return redirect()->route('dashboard.index');
            } else {
                throw new \Exception('Login failed. Invalid credentials.');
            }
        } catch (\Exception $e) {
            return back()->withErrors(['email' => $e->getMessage()])
                ->withInput($request->only('email'));
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
