<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OnlineUsers extends Model
{
    use HasFactory;
    protected $table = 'online_users';
    protected $guarded=[
        'id'
    ];
}
