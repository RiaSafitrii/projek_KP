<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('Password123'),
            'role' => 'admin',
            'status' => 'actived',
        ]);

        \App\Models\User::create([
            'name' => 'Operator',
            'email' => 'operator@example.com',
            'password' => bcrypt('Password123'),
            'role' => 'operator',
            'status' => 'actived',
        ]);


        // Memanggil seeder lain
        $this->call([
            PublicInfoSeeder::class,
            SosialMediaSeeder::class,
            WebOptionSeeder::class,
        ]);
    }
}
