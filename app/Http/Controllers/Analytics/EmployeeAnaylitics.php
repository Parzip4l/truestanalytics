<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Analytics\Employee;

class EmployeeAnaylitics extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function demographic()
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

        // Hitung Total Karyawan
        $employeeCount = Employee::where('unit_bisnis', $unitBisnis)->count();

        // Hitung Laki Laki
        $lakilaki = Employee::where('unit_bisnis', $unitBisnis)
        ->where('jenis_kelamin', 'Laki-Laki')                    
        ->count();

        // Hitung Perempuan
        $perempuan = Employee::where('unit_bisnis', $unitBisnis)
        ->where('jenis_kelamin', 'Perempuan')                    
        ->count();

        // Ambil data karyawan      
        $employees = Employee::where('unit_bisnis', $unitBisnis)->get();
        $ageGroups = $this->calculateAgeGroups($employees);

        // Karyawan Berdasarkan Sekolah
        $educationLevels = $this->calculateEducationLevels($employees);

        // Karyawan Berdasarkan Religion
        $agama = $this->agamaCalculate($employees);
        

        $total = $lakilaki + $perempuan;
        $lakilakiPercentage = round(($total > 0) ? ($lakilaki / $total) * 100 : 0);
        $perempuanPercentage = round(($total > 0) ? ($perempuan / $total) * 100 : 0);
        return view('pages.employee.demographic', compact('employeeCount','lakilaki','perempuan','lakilakiPercentage', 'perempuanPercentage','ageGroups','educationLevels','agama'));
    }

    private function calculateAgeGroups($employees)
    {
        // Inisialisasi array untuk menyimpan jumlah karyawan dalam setiap kategori usia
        $ageGroups = [
            '<20' => 0,
            '20-30' => 0,
            '31-40' => 0,
            '41-50' => 0,
            '>50' => 0,
        ];
        
        // Loop melalui setiap karyawan
        foreach ($employees as $employee) {
            // Hitung usia karyawan
            $birthdate = $employee->tanggal_lahir;
            $age = date_diff(date_create($birthdate), date_create('now'))->y;
            
            // Kelompokkan karyawan ke dalam kategori usia
            if ($age < 20) {
                $ageGroups['<20']++;
            } elseif ($age >= 20 && $age <= 30) {
                $ageGroups['20-30']++;
            } elseif ($age >= 31 && $age <= 40) {
                $ageGroups['31-40']++;
            } elseif ($age >= 41 && $age <= 50) {
                $ageGroups['41-50']++;
            } else {
                $ageGroups['>50']++;
            }
        }
        
        return $ageGroups;
    }

    private function calculateEducationLevels($employees)
    {
        // Inisialisasi array untuk menyimpan jumlah karyawan dalam setiap tingkat pendidikan
        $educationLevels = [
            'SD' => 0,
            'SMP' => 0,
            'SMA' => 0,
            'DIPLOMA' => 0,
            'SARJANA' => 0,
            'MAGISTER' => 0,
            'DOCTOR' => 0,
            'Other' => 0,
        ];
        
        // Loop melalui setiap karyawan
        foreach ($employees as $employee) {
            // Ambil tingkat pendidikan terakhir karyawan
            $educationLevel = $employee->pendidikan_trakhir;
            
            // Periksa dan kelompokkan karyawan ke dalam tingkat pendidikan yang sesuai
            switch ($educationLevel) {
                case 'SD':
                    $educationLevels['SD']++;
                    break;
                case 'SMP':
                    $educationLevels['SMP']++;
                    break;
                case 'SMA':
                    $educationLevels['SMA']++;
                    break;
                case 'DIPLOMA':
                    $educationLevels['DIPLOMA']++;
                    break;
                case 'SARJANA':
                    $educationLevels['SARJANA']++;
                    break;
                case 'MAGISTER':
                    $educationLevels['MAGISTER']++;
                    break;
                case 'DOCTOR':
                    $educationLevels['DOCTOR']++;
                    break;
                default:
                    $educationLevels['Other']++;
                    break;
            }
        }
        
        return $educationLevels;
    }

    private function agamaCalculate($employees)
    {
        $agamas  = [
            'Islam' => 0,
            'Christian' => 0,
            'Buddha' => 0,
            'Katolik' => 0,
            'Hindu' => 0,
            'Other' => 0,
        ];

        foreach ($employees as $employee) {
            // Ambil tingkat pendidikan terakhir karyawan
            $agama = $employee->agama;
            
            // Periksa dan kelompokkan karyawan ke dalam tingkat pendidikan yang sesuai
            switch ($agama) {
                case 'Islam':
                    $agamas['Islam']++;
                    break;
                case 'Christian':
                    $agamas['Christian']++;
                    break;
                case 'Buddha':
                    $agamas['Buddha']++;
                    break;
                case 'Katolik':
                    $agamas['Katolik']++;
                    break;
                case 'Hindu':
                    $agamas['Hindu']++;
                    break;
                case 'MAGISTER':
                    $agamas['MAGISTER']++;
                    break;
                default:
                    $agamas['Other']++;
                    break;
            }
        }
        return $agamas;
    }
}
