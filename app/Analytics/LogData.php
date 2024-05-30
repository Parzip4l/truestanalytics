<?php

namespace App\Analytics;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogData extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'logs';
}
