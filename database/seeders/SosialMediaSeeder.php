<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SosialMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sosial_media')->insert([
            [
                'name' => 'facebook',
                'url' => 'https://www.facebook.com/',
                'icon_code' => 'fab fa-facebook',
            ],
            [
                'name' => 'twitter',
                'url' => 'https://www.twitter.com/',
                'icon_code' => 'fab fa-twitter',
            ],
            [
                'name' => 'instagram',
                'url' => 'https://www.instagram.com/',
                'icon_code' => 'fab fa-instagram',
            ],
            [
                'name' => 'youtube',
                'url' => 'https://www.youtube.com/',
                'icon_code' => 'fab fa-youtube',
            ],

        ]);
    }
}
