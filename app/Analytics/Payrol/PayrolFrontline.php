<?php

namespace App\Analytics\Payrol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrolFrontline extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'payrollns';
}
