<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebOption extends Model
{
    use HasFactory;
    protected $table = 'web_option';
    protected $guarded=[
        'id'
    ];

    public static function getOption($name)
    {
        // Mengambil data favicon
        $data = self::select('name', 'value', 'path_file')->where('name', $name)->first();
        return $data; // fallback ke default jika tidak ada
    }
}
