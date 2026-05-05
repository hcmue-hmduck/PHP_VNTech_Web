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
        Schema::create('cart_items', function (Blueprint $collection) {
            $collection->string('ma_gio_hang')->index();
            $collection->string('ma_bien_the')->index(); 
            $collection->integer('so_luong');
            
            // Đảm bảo một cặp (giỏ hàng, sản phẩm) là duy nhất để tránh trùng lặp
            $collection->unique(['ma_gio_hang', 'ma_bien_the']); 
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
