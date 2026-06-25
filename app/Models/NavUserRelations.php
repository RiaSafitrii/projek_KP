<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NavUserRelations extends Model
{
    use HasFactory;
    protected $table = 'nav_user_relations';
    protected $guarded=[
        'id'
    ];
}
