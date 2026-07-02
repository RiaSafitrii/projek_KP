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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('operator');
            $table->text('content')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamp('publish_date')->nullable();
            $table->integer('views')->default(0);  // Menyimpan jumlah views
            $table->integer('shares')->default(0);  // Menyimpan jumlah shares
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
            $table->foreignId('operator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->string('status')->default('actived')->after('thumbnail');
            $table->date('finaldate_of_announcement')->nullable()->after('publish_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('news', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'finaldate_of_announcement'
            ]);
        });
    }
};
