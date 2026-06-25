<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('web_option')->insert([
            [
                'name' => 'webName',
                'value' => 'Portal Dinas Kesehatan Kota Bandar Lampung', // Ganti dengan nama website Anda
                'path_file' => null, // File path bisa kosong (null)
            ],
            [
                'name' => 'domain',
                'value' => 'dinkes.bandarlampungkota.go.id', // Ganti dengan domain website Anda
                'path_file' => null,
            ],
            [
                'name' => 'logo',
                'value' => '-',
                'path_file' => 'assets/images/AtcBxhYuOYuZdI3jpTOagCa7XMAAVY2jRBgjhiFH.png', // Ganti dengan path logo jika ada
            ],
            [
                'name' => 'logoWhite',
                'value' => '-',
                'path_file' => null,
            ],
            [
                'name' => 'favicon',
                'value' => '-',
                'path_file' => 'assets/images/IHCOmOKNPybFc2xjNLvaJoMbzdkLSFEPW7ZH8Jwc.png', // Ganti dengan path favicon jika ada
            ],
            [
                'name' => 'email',
                'value' => 'contact@example.com', // Ganti dengan email kontak Anda
                'path_file' => null,
            ],
            [
                'name' => 'nomor',
                'value' => '+62 8000000', // Ganti dengan nomor telepon Anda
                'path_file' => null,
            ],
            [
                'name' => 'alamat',
                'value' => 'Alamat', // Ganti dengan alamat Anda
                'path_file' => null,
            ],
            [
                'name' => 'deskripsi',
                'value' => 'lorem',
                'path_file' => null,
            ],
            [
                'name' => 'maps',
                'value' => 'https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d127108.0555541327!2d105.070694!3d-5.397654!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40d25fa28dc0cf%3A0xcd0378ad73f1cb22!2sKantor%20Bupati%20Pesawaran!5e0!3m2!1sen!2sus!4v1739420466905!5m2!1sen!2sus',
                'path_file' => null,
            ],
            [
                'name' => 'authBackground',
                'value' => '-',
                'path_file' => 'assets/images/auth-bg.jpg',
            ],
        ]);
    }
}
