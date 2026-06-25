<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PublicInfo extends Model
{
    use HasFactory;
    protected $table = 'public_info';
    protected $guarded=[
        'id'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($publicinfo) {
            // Cek apakah info_name tidak kosong
            if (!empty($publicinfo->info_name)) {
                // Generate slug dari info_name
                $slug = Str::slug($publicinfo->info_name);

                // Cek apakah slug sudah ada dan buat unik
                $count = PublicInfo::where('slug', 'like', $slug . '%')->count();
                if ($count) {
                    $slug = $slug . '-' . ($count + 1);
                }

                $publicinfo->slug = $slug;
            } else {
                // Jika info_name kosong, set slug null
                $publicinfo->slug = null;
            }
        });

        static::updating(function ($publicinfo) {
            // Cek jika info_name berubah
            if ($publicinfo->isDirty('info_name')) {
                // Cek apakah info_name tidak kosong
                if (!empty($publicinfo->info_name)) {
                    $slug = Str::slug($publicinfo->info_name);
                    $count = PublicInfo::where('slug', 'like', $slug . '%')->count();
                    if ($count) {
                        $slug = $slug . '-' . ($count + 1);
                    }
                    $publicinfo->slug = $slug;
                } else {
                    // Jika info_name kosong, set slug null
                    $publicinfo->slug = null;
                }
            }
        });
    }

}
