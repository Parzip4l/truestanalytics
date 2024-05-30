<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
// Model
use App\Analytics\Employee;
use App\Analytics\Payrol\PayrolManagement;
use App\Analytics\Payrol\PayrolFrontline;
use App\Analytics\Payrol\PayrolAnggota;

class PayrolAnaylitics extends Controller
{
    public function index()
    {
        $userData = session('userData');
        $userProfile = session('userProfile');
        
        // Pastikan userData dan userProfile ada sebelum digunakan
        if (!$userData || !$userProfile) {
            throw new \Exception('User data or profile not found in session.');
        }

        // Periode 
        $today = now();
        $dates = $this->getPeriod($today);
        $startDate = $dates['startDate'];
        $endDate = $dates['endDate'];
        
        // Dapatkan token dan unit_bisnis pengguna
        $token = $userData['token'];
        $unitBisnis = $userProfile['employee']['unit_bisnis'];

        // Ambil data karyawan      
        $employees = Employee::where('unit_bisnis', $unitBisnis)->get();

        // Data Total Gaji Managent 
        if ($unitBisnis === 'Kas') {
            $ManagementData = $this->countManagement($unitBisnis, $startDate, $endDate);
            // Data Total Gaji Frontline 
            $FrontlineData = $this->countAnggota($startDate, $endDate);
            $totalPeriode = $ManagementData + $FrontlineData;
        }else{
            $ManagementData = $this->countManagement($unitBisnis, $startDate, $endDate);
            // Data Total Gaji Frontline 
            $FrontlineData = $this->countFrontline($startDate, $endDate);
            $totalPeriode = $ManagementData + $FrontlineData;
        }


        // Data Statistik Payroll
        if ($unitBisnis === 'Kas') {
            $frontlineSalaries = PayrolAnggota::all();
            $managementSalaries = PayrolManagement::where('unit_bisnis', $unitBisnis)->get();
        } else {
            $managementSalaries = PayrolManagement::where('unit_bisnis', $unitBisnis)->get();
            $frontlineSalaries = PayrolFrontline::all();
        }

        $currentYear = now()->year;
        // Mendapatkan Gaji Per tahun dan Per Periode
        $managementDataPeriode = $managementSalaries->filter(function ($salary) use ($currentYear) {
            return $salary->created_at->year === $currentYear;
        })
        ->groupBy(function ($salary) {
            return $salary->created_at->format('M Y');
        })
        ->map->sum('net_salary');
        
        $frontlineDataPeriode = $frontlineSalaries->filter(function ($salary) use ($currentYear) {
            return $salary->created_at->year === $currentYear;
        })
        ->groupBy(function ($salary) {
            return $salary->created_at->format('M Y');
        })
        ->map->sum('thp');

        $managementDataYearly = $managementSalaries->groupBy(function ($salary) {
            return $salary->created_at->format('Y');
        })->map->sum('net_salary');
        
        $frontlineDataYearly = $frontlineSalaries->groupBy(function ($salary) {
            return $salary->created_at->format('Y');
        })->map->sum('thp');
        
        return view('pages.payrol.index', compact('employees','ManagementData','FrontlineData','totalPeriode'
        ,'managementDataPeriode','frontlineDataPeriode','managementDataYearly','frontlineDataYearly'));
    }

    private function getPeriod($today)
    {
        $startDate = $today->day >= 21 ? $today->copy()->day(20) : $today->copy()->subMonth()->day(21);
        $endDate = $today->day >= 21 ? $today->copy()->addMonth()->day(20) : $today->copy()->day(20);

        return ['startDate' => $startDate, 'endDate' => $endDate];
    }

    private function countManagement($unitBisnis, $startDate, $endDate)
    {
        try {
            $payrollData = PayrolManagement::where('unit_bisnis', $unitBisnis)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
    
            $payrollTotal = $payrollData->sum('net_salary');
    
            return $payrollTotal;
        } catch (\Exception $e) {
            // Penanganan kesalahan
            return null;
        }
    }

    private function countFrontline($startDate, $endDate)
    {
        try {
            $payrollData = PayrolFrontline::whereBetween('created_at', [$startDate, $endDate])
                ->get();
    
            $payrollTotal = $payrollData->sum('thp');
    
            return $payrollTotal;
        } catch (\Exception $e) {
            // Penanganan kesalahan
            return null;
        }
    }

    private function countAnggota($startDate, $endDate)
    {
        try {
            $payrollData = PayrolAnggota::whereBetween('created_at', [$startDate, $endDate])
                ->get();
    
            $payrollTotal = $payrollData->sum('thp');
    
            return $payrollTotal;
        } catch (\Exception $e) {
            // Penanganan kesalahan
            return null;
        }
    }
    
}
