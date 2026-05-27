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
        Schema::create('flash_sale_items', function (Blueprint $collection) {
            $collection->string('ma_chi_tiet_flash_sales')->index();
            $collection->string('ma_flash_sales');
            $collection->string('ma_bien_the');
            $collection->decimal('gia_flash_sale', 15, 2);
            $collection->integer('so_luong_gioi_han'); // Tổng số lượng bán trong đợt này
            $collection->integer('so_luong_da_ban')->default(0);
            $collection->integer('gioi_han_moi_nguoi')->default(1);
            $collection->string('trang_thai')->default('active');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sale_items');
    }
};
