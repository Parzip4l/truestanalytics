<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Analytics\Employee;


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
            
            // Pastikan userData dan userProfile ada sebelum digunakan
            if (!$userData || !$userProfile) {
                throw new \Exception('User data or profile not found in session.');
            }
            
            // Dapatkan token dan unit_bisnis pengguna
            $token = $userData['token'];
            $unitBisnis = $userProfile['employee']['unit_bisnis'];
            
            // Dapatkan profil pengguna
            $profileResponse = $this->fetchUserProfile($userData);
            
            // Dapatkan jumlah karyawan
            $employeeCount = $this->EmployeeCount();
            // Dapatkan jumlah pengunjung unik
            $uniqueVisitorsCount = $this->getUniqueVisitorsCount();

            $userProfile = null;
            if ($profileResponse && $profileResponse->ok()) {
                $userProfile = $profileResponse->json()['data'];
            } else {
                // Log pesan kesalahan jika gagal mendapatkan profil pengguna
                \Log::error('Failed to fetch user profile.');
                // Handle the error accordingly, maybe display a message or redirect
            }

            // Kembalikan view dashboard dengan data yang diperlukan
            return view('dashboard', compact('userData', 'userProfile','uniqueVisitorsCount','employeeCount'));
        } catch (\Exception $e) {
            // Tangani kesalahan dan log pesan kesalahan
            \Log::error($e->getMessage());
            // Tampilkan halaman error_page dengan pesan kesalahan
            return view('error_page'.$e);
        }
    }

    private function getUniqueVisitorsCount()
    {
        try {
            $uniqueVisitorsResponse = Http::get('https://hris.truest.co.id/api/v1/unique-visitors');

            if ($uniqueVisitorsResponse->ok()) {
                $data = $uniqueVisitorsResponse->json();
                return [
                    'current_unique_visitor_count' => $data['current_unique_visitor_count'],
                    'current_page_view_count' => $data['current_page_view_count'],
                    'previous_unique_visitor_count' => $data['previous_unique_visitor_count'],
                    'previous_page_view_count' =>$data['previous_page_view_count'],
                    'visitor_count_change' => $data['visitor_count_change'],
                    'visitor_count_change_percentage' => $data['visitor_count_change_percentage'],
                    'page_view_count_change' => $data['page_view_count_change'],
                    'page_view_count_change_percentage' => $data['page_view_count_change_percentage'],
                ];
            } else {
                throw new \Exception('Failed to fetch unique visitors count.');
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return [
                'unique_visitor_count' => 0,
                'page_view_count' => 0,
            ]; // Return 0 if there's an error
        }
    }

    private function EmployeeCount()
    {
        $userProfile = session('userProfile');
        $unitBisnis = $userProfile['employee']['unit_bisnis'];
        $employeeCount = Employee::where('unit_bisnis', $unitBisnis)->count();

        return $employeeCount;
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
