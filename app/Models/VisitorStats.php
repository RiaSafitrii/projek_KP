<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupNavigation extends Model
{
    use HasFactory;
    protected $table = 'group_navigation';
    protected $guarded=[
        'id'
    ];

    public function navigations()
    {
        return $this->hasMany(Navigation::class);
    }
}
