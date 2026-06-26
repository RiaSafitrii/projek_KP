<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('public_info', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->nullable()->unique();
            $table->string('info_code')->nullable()->unique();
            $table->string('code_sama')->nullable();
            $table->string('info_name')->nullable();
            $table->text('info_value')->nullable();
            $table->string('info_name_url')->nullable();
            $table->string('info_url')->nullable();
            $table->string('images')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_info');
    }
};
