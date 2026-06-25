<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SosialMedia extends Model
{
    use HasFactory;
    protected $table = 'sosial_media';
    protected $guarded=[
        'id'
    ];

    public static function getSoaialMedia($name)
    {
        // Mengambil data favicon
        $data = self::select('name', 'value', 'icon_code')->where('name', $name)->first();
        return $data; // fallback ke default jika tidak ada
    }
}
