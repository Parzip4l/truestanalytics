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

class TimeOffAnaylitics extends Controller
{
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

        // Periode 
        $today = now();
        $dates = $this->getPeriod($today);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];

        // Employee Data
        $employees = Employee::where('unit_bisnis', $unitBisnis)->where('resign_status',0)->get();
        $employeeCount = Employee::where('unit_bisnis', $unitBisnis)
        ->where('resign_status',0)->count();

        // TanpaKetarangan
        $tanpaKeterangan = $this->tanpaKeterangan($employees, $unitBisnis, $today);
        // WFH
        $dataWfe =  $this->DataWFH($employees, $unitBisnis, $today, $startDate, $endDate);
        // Sakit
        $dataSakit = $this->DataSakit($employees, $unitBisnis, $today, $startDate, $endDate);
        // Data Izin
        $dataIzin = $this->DataIzin($employees, $unitBisnis, $today, $startDate, $endDate);

        return view ('pages.attendance.time-off',compact('employees','tanpaKeterangan','dataWfe','dataSakit','dataIzin'));
    }

    private function tanpaKeterangan($employees, $unitBisnis, $today)
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

    private function DataWFH($employees, $unitBisnis, $today, $startDate, $endDate)
    {
        $DataHadir = DB::table('karyawan')
            ->leftJoin('absens', function ($join) use ($today) {
                $join->on('karyawan.nik', '=', 'absens.nik')
                    ->whereDate('absens.tanggal', $today);
            })
            ->where('unit_bisnis',$unitBisnis)
            ->where('resign_status',0)
            ->where('absens.status', 'WFE')
            ->count();
        
        $dataWfh = DB::table('absens')
            ->join('karyawan', 'absens.nik', '=', 'karyawan.nik')
            ->select('absens.nik', 'karyawan.nama','karyawan.organisasi', DB::raw('count(*) as total_absensi'))
            ->where('karyawan.unit_bisnis', $unitBisnis)
            ->where('absens.status','WFE')
            ->whereBetween('absens.tanggal', [$startDate, $endDate])
            ->groupBy('absens.nik', 'karyawan.nama','karyawan.organisasi')
            ->orderByDesc('total_absensi')
            ->limit(10)
            ->get();

       // Jumlah total karyawan
        $totalKaryawan = Employee::where('unit_bisnis', $unitBisnis)
        ->where('resign_status', 0)
        ->count();

        // Hitung persentase kehadiran
        $persentaseHadir = ($totalKaryawan > 0) ? ($DataHadir / $totalKaryawan) * 100 : 0;

        return ['jumlah' => $DataHadir, 'persentase' => $persentaseHadir, 'dataWfh' => $dataWfh];
    }

    private function DataSakit($employees, $unitBisnis, $today, $startDate, $endDate)
    {
        $DataHadir = DB::table('karyawan')
            ->leftJoin('absens', function ($join) use ($today) {
                $join->on('karyawan.nik', '=', 'absens.nik')
                    ->whereDate('absens.tanggal', $today);
            })
            ->where('unit_bisnis',$unitBisnis)
            ->where('resign_status',0)
            ->where('absens.status', 'Sakit')
            ->count();

       // Jumlah total karyawan
        $totalKaryawan = Employee::where('unit_bisnis', $unitBisnis)
        ->where('resign_status', 0)
        ->count();

        // Data Looping
        $DataSakit = DB::table('absens')
            ->join('karyawan', 'absens.nik', '=', 'karyawan.nik')
            ->select('absens.nik', 'karyawan.nama','karyawan.organisasi', DB::raw('count(*) as total_absensi'))
            ->where('karyawan.unit_bisnis', $unitBisnis)
            ->where('absens.status','Sakit')
            ->whereBetween('absens.tanggal', [$startDate, $endDate])
            ->groupBy('absens.nik', 'karyawan.nama','karyawan.organisasi')
            ->orderByDesc('total_absensi')
            ->limit(10)
            ->get();

        // Hitung persentase kehadiran
        $persentaseHadir = ($totalKaryawan > 0) ? ($DataHadir / $totalKaryawan) * 100 : 0;

        return ['jumlah' => $DataHadir, 'persentase' => $persentaseHadir, 'dataSakit' => $DataSakit];
    }

    private function DataIzin($employees, $unitBisnis, $today, $startDate, $endDate)
    {
        $DataHadir = DB::table('karyawan')
            ->leftJoin('absens', function ($join) use ($today) {
                $join->on('karyawan.nik', '=', 'absens.nik')
                    ->whereDate('absens.tanggal', $today);
            })
            ->where('unit_bisnis',$unitBisnis)
            ->where('resign_status',0)
            ->where('absens.status', 'Izin')
            ->count();

       // Jumlah total karyawan
        $totalKaryawan = Employee::where('unit_bisnis', $unitBisnis)
        ->where('resign_status', 0)
        ->count();

        // Data Izin
        $DataIzin = DB::table('absens')
            ->join('karyawan', 'absens.nik', '=', 'karyawan.nik')
            ->select('absens.nik', 'karyawan.nama','karyawan.organisasi', DB::raw('count(*) as total_absensi'))
            ->where('karyawan.unit_bisnis', $unitBisnis)
            ->where('absens.status','Izin')
            ->whereBetween('absens.tanggal', [$startDate, $endDate])
            ->groupBy('absens.nik', 'karyawan.nama','karyawan.organisasi')
            ->orderByDesc('total_absensi')
            ->limit(10)
            ->get();

        // Hitung persentase kehadiran
        $persentaseHadir = ($totalKaryawan > 0) ? ($DataHadir / $totalKaryawan) * 100 : 0;

        return ['jumlah' => $DataHadir, 'persentase' => $persentaseHadir, 'dataIzin' => $DataIzin];
    }
}
