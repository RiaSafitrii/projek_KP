<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;
    protected $table = 'categories';
    protected $guarded=[
        'id'
    ];
    public function consultationServices()
    {
        return $this->hasMany(ConsultationServices::class, 'category_id');
    }
}
