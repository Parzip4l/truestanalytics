<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Analytics\Employee;
use App\Analytics\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AttendanceAnaylitics extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userData = session('userData');
        $userProfile = session('userProfile');
        
        // Pastikan userData dan userProfile ada sebelum digunakan
        if (!$userData || !$userProfile) {
            throw new \Exception('User data or profile not found in session.');
        }
        
        // Dapatkan token dan unit_bisnis pengguna
        $token = $userData['token'];
        $unitBisnis = $userProfile['employee']['unit_bisnis'];

        // Employee Data
        $employees = Employee::where('unit_bisnis', $unitBisnis)->where('resign_status',0)->get();
        $employeeCount = Employee::where('unit_bisnis', $unitBisnis)
        ->where('resign_status',0)->count();

        // Periode 
        $today = now();
        $dates = $this->getPeriod($today);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Kehadiran
        $hadir = $this->kehadiran($employees, $unitBisnis, $today);
        $absen = $this->ketidakhadiran($employees, $unitBisnis, $today);

        // Persentase Kehadiran dan Ketidakhadiran
        $persentaseHadir = $hadir['persentase'];
        $persentaseAbsen = $absen['persentase'];

        // Data untuk Chart
        $chartData = [
            'labels' => ['Hadir', 'Tidak Hadir'],
            'datasets' => [
                [
                    'data' => [$persentaseHadir, $persentaseAbsen],
                    'backgroundColor' => ['#4CAF50', '#F44336']
                ]
            ]
        ];

        // Grafik Bar
        $AbsenByday = $this->byDay($employees, $unitBisnis, $today, $startDate , $endDate);
        
        return view ('pages.attendance.index',compact('hadir','employeeCount','absen','chartData','AbsenByday'));
    }

    private function kehadiran($employees, $unitBisnis, $today)
    {
        $DataHadir = Attendance::join('karyawan', 'absens.nik', '=', 'karyawan.nik')
                    ->where('karyawan.unit_bisnis', $unitBisnis)
                    ->where('tanggal',$today)
                    ->where('status', 'H')
                    ->select('absens.*', 'karyawan.*')
                    ->count();

       // Jumlah total karyawan
        $totalKaryawan = Employee::where('unit_bisnis', $unitBisnis)
        ->where('resign_status', 0)
        ->count();

        // Hitung persentase kehadiran
        $persentaseHadir = ($totalKaryawan > 0) ? ($DataHadir / $totalKaryawan) * 100 : 0;

        return ['jumlah' => $DataHadir, 'persentase' => $persentaseHadir];
    }

    private function ketidakhadiran($employees, $unitBisnis, $today)
    {
        $karyawanTidakAbsenHariIni = DB::table('karyawan')
            ->leftJoin('absens', function ($join) use ($today) {
                $join->on('karyawan.nik', '=', 'absens.nik')
                    ->whereDate('absens.tanggal', $today);
            })
            ->where('unit_bisnis',$unitBisnis)
            ->where('resign_status',0)
            ->whereNull('absens.nik')
            ->count();

         // Jumlah total karyawan
        $totalKaryawan = Employee::where('unit_bisnis', $unitBisnis)
            ->where('resign_status', 0)
            ->count();

        // Hitung persentase ketidakhadiran
        $persentaseTidakAbsen = ($totalKaryawan > 0) ? ($karyawanTidakAbsenHariIni / $totalKaryawan) * 100 : 0;
        return ['jumlah' => $karyawanTidakAbsenHariIni, 'persentase' => $persentaseTidakAbsen];
    }

    private function getPeriod($today)
    {
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        return ['startDate' => $startDate, 'endDate' => $endDate];
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
