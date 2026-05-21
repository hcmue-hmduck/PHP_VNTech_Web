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
        Schema::create('categories', function (Blueprint $collection) {
            $collection->string('ma_danh_muc')->unique();
            $collection->string('ma_danh_muc_cha');
            $collection->string('ten_danh_muc')->index(); 
            $collection->string('logo_url')->nullable();
            $collection->string('trang_thai')->default('active');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
