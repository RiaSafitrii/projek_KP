<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitorStats extends Model
{
    use HasFactory;
    protected $table = 'visitor_stats';
    protected $guarded=[
        'id'
    ];
}
