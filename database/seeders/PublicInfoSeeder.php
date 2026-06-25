<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PublicInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('public_info')->insert([
            [
                'slug' => 'struktur-organisasi',
                'info_code' => 'struktur_organisasi',
            ],
            [
                'slug' => 'tugas-dan-fungsi',
                'info_code' => 'tugas_fungsi',
            ],

        ]);
    }
}
