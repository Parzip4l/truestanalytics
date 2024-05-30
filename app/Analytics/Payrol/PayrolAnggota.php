<?php

namespace App\Analytics\Payrol;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayrolAnggota extends Model
{
    use HasFactory;
    protected $connection = 'mysql_secondary';
    protected $table = 'payrolls';
}
