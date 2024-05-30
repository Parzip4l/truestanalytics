<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Analytics\Employee;
use App\Analytics\Attendance;
use App\Analytics\LogData;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


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
            $employees = Employee::where('unit_bisnis', $unitBisnis)->where('resign_status',0)->get();
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

            $today = now();
            $dates = $this->getPeriod($today);
            $startDate = $dates['startDate'];
            $endDate = $dates['endDate'];

            $AbsenByday = $this->byDay($employees, $unitBisnis, $today, $startDate , $endDate);

            // User Aktif
            $userAktif = $this->mostActiveUsers($unitBisnis, $startDate, $endDate);
            // Kembalikan view dashboard dengan data yang diperlukan
            return view('dashboard', compact('userData', 'userProfile','uniqueVisitorsCount','employeeCount','AbsenByday','userAktif'));
        } catch (\Exception $e) {
            // Tangani kesalahan dan log pesan kesalahan
            \Log::error($e->getMessage());
            // Tampilkan halaman error_page dengan pesan kesalahan
            return view('error_page'.$e);
        }
    }

    private function getPeriod($today)
    {
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }

    private function mostActiveUsers($unitBisnis, $startDate, $endDate)
    {
        // Query untuk menghitung jumlah absensi berdasarkan unit bisnis dalam rentang periode
        $mostActiveUsers = DB::table('absens')
            ->join('karyawan', 'absens.nik', '=', 'karyawan.nik')
            ->select('absens.nik', 'karyawan.nama','karyawan.organisasi', DB::raw('count(*) as total_absensi'))
            ->where('karyawan.unit_bisnis', $unitBisnis)
            ->where('absens.status','H')
            ->whereBetween('absens.tanggal', [$startDate, $endDate])
            ->groupBy('absens.nik', 'karyawan.nama','karyawan.organisasi')
            ->orderByDesc('total_absensi')
            ->limit(5) // Ambil 10 pengguna paling aktif
            ->get();

        return $mostActiveUsers;
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
        $employeeCount = Employee::where('unit_bisnis', $unitBisnis)->where('resign_status',0)->count();

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

    private function byDay($employees, $unitBisnis, $today, $startDate, $endDate)
    {
        // Query untuk mendapatkan data kehadiran pada rentang tanggal
        $dataHadir = Attendance::join('karyawan', 'absens.nik', '=', 'karyawan.nik')
                        ->where('karyawan.unit_bisnis', $unitBisnis)
                        ->whereBetween('tanggal', [$startDate, $endDate])
                        ->where('status', 'H')
                        ->select('absens.*', 'karyawan.*')
                        ->get();

        // Data Absensi 
        $labels = [];
        $currentDate = $startDate->copy();
        while ($currentDate->lte($endDate)) {
            $labels[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Inisialisasi array data untuk chart
        $dataAbsenByDay = [
            'labels' => $labels,
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'backgroundColor' => 'rgb(54, 162, 235)', // Warna biru untuk data hadir
                    'data' => [],
                ],
                [
                    'label' => 'Tidak Hadir',
                    'backgroundColor' => 'rgb(255, 99, 132)', // Warna merah untuk data tidak hadir
                    'data' => [],
                ]
            ]
        ];

        // Hitung jumlah kehadiran dan ketidakhadiran untuk setiap tanggal
        foreach ($labels as $label) {
            $hadirCount = $dataHadir->where('tanggal', $label)->count();
            $tidakHadirCount = $employees->count() - $hadirCount; // Jumlah karyawan dikurangi jumlah hadir untuk mendapatkan tidak hadir
            $dataAbsenByDay['datasets'][0]['data'][] = $hadirCount;
            $dataAbsenByDay['datasets'][1]['data'][] = $tidakHadirCount;
        }

        // Kembalikan data absensi per hari
        return $dataAbsenByDay;
    }
}
