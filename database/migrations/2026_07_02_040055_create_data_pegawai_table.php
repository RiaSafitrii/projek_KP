<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_pegawai', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->string('name');

            $table->foreignId('jabatan_id')
                  ->nullable()
                  ->constrained('jabatan_pegawai')
                  ->nullOnDelete();

            $table->foreignId('bidang_id')
                  ->nullable()
                  ->constrained('bidang_pegawai')
                  ->nullOnDelete();

            $table->string('foto')->nullable();
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pegawai');
    }
};