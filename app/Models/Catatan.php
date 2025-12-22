<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    protected $fillable = [
        'user_id',
        'kategori',
        'subkategori',
        'tipe',
        'nominal',
        'tanggal',
        'keterangan',
    ];
}
