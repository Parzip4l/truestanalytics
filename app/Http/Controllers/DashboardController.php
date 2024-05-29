<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $userData = session('userData');
            $userProfile = session('userProfile');
            // Check if 'name' key exists in $userData
            if (!isset($userData['name'])) {
                throw new \Exception('Name not found in user data.');
            }

            // Check if 'token' key exists in $userData
            if (!isset($userData['token'])) {
                throw new \Exception('Token not found in user data.');
            }

            // Fetch user profile data if available
            $profileResponse = $this->fetchUserProfile($userData);

            $userProfile = null;
            if ($profileResponse && $profileResponse->ok()) {
                $userProfile = $profileResponse->json()['data'];
            } else {
                \Log::error('Failed to fetch user profile.');
                // Handle the error accordingly, maybe display a message or redirect
            }

            return view('dashboard', compact('userData', 'userProfile'));
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return view('error_page' .$e);
        }
    }

    
    private function fetchUserProfile($userData)
    {
        // Assuming $userData contains a token
        $token = $userData['token'];

        // Get user identifier from session
        $userIdentifier = $userData['name']; // Use 'name' instead of 'nama'

        // Fetch user profile using the token and user identifier
        $profileResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('https://hris.truest.co.id/api/v1/profile/' . $userIdentifier);

        return $profileResponse;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
