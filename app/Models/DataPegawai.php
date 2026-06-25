<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DataPegawai extends Model
{
    use HasFactory;
    protected $table = 'data_pegawai';
    protected $guarded=[
        'id'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($data) {
            // Generate slug from the title
            $slug = Str::slug($data->name);

            // Check if the slug already exists and make it unique
            $count = DataPegawai::where('slug', 'like', $slug . '%')->count();
            if ($count) {
                $slug = $slug . '-' . ($count + 1);
            }

            $data->slug = $slug;
        });

        static::updating(function ($data) {
            // Jika title berubah, perbarui slug
            if ($data->isDirty('name')) {
                $slug = Str::slug($data->name);
                $count = DataPegawai::where('slug', 'like', $slug . '%')->count();
                if ($count) {
                    $slug = $slug . '-' . ($count + 1);
                }
                $data->slug = $slug;
            }
        });
    }
}
