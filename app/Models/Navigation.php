<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;
    protected $table = 'navigation';
    protected $guarded=[
        'id'
    ];

    public function group()
    {
        return $this->belongsTo(GroupNavigation::class, 'group_navigation_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'nav_user_relations', 'navigation_id', 'user_id')
        ->withTimestamps();
    }
}
