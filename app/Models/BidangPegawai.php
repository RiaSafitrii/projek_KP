<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BidangPegawai extends Model
{
    use HasFactory;
    protected $table = 'bidang_pegawai';
    protected $guarded=[
        'id'
    ];
}
