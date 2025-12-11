<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DanaDarurat extends Model
{
    use HasFactory;

    protected $table = 'dana_darurats';

    protected $fillable = [
        'user_id',
        'bulan',
        'minggu',
        'status',
        'sumber',
        'jumlah',
        'total',
    ];
}
