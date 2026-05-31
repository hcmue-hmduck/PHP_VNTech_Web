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
        Schema::create('user_address', function (Blueprint $collection) {
            $collection->string('ma_dia_chi')->index();
            $collection->string('ma_nguoi_dung')->index();
            $collection->string('ho_ten');
            $collection->string('so_dien_thoai');
            $collection->string('dia_chi_chi_tiet');
            $collection->string('tinh_thanh');
            $collection->string('quan_huyen');
            $collection->string('phuong_xa');
            
            $collection->boolean('is_default')->default(false);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_address');
    }
};
