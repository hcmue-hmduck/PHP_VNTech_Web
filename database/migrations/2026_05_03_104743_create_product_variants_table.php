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
        Schema::create('product_variants', function (Blueprint $collection) {
            $collection->string('ma_san_pham')->index(); 
            $collection->string('ma_bien_the'); 
            $collection->string('ten_bien_the');
    
            $collection->decimal('gia_ban', 15, 2);
            $collection->decimal('gia_niem_yet', 15, 2)->nullable();
            $collection->integer('so_luong_ton_kho')->default(0);
            $collection->integer('da_ban')->default(0);
            $collection->array('thong_so_ky_thuat_rieng');
            
            $collection->string('link_anh_bien_the')->nullable();

            $collection->string('trang_thai')->default('active');
            $collection->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
