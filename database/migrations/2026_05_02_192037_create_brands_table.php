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
        Schema::create('brands', function (Blueprint $collection) {
            $collection->string('ma_thuong_hieu')->unique();
            $collection->string('ten_thuong_hieu')->index(); // Thêm index để tìm kiếm nhanh
            $collection->text('mo_ta')->nullable(); // Đổi sang text cho mô tả dài
            $collection->string('logo_url')->nullable();
            $collection->string('slug')->unique();
            $collection->string('trang_thai')->default('active');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
