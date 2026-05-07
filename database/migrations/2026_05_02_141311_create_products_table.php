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
        Schema::create('products', function (Blueprint $collection) {
            $collection->uuid('ma_san_pham')->unique();
            $collection->string('ten_san_pham');
            $collection->string('slug')->unique();

            // $collection->string('ma_danh_muc')->index();
            $collection->string('ma_thuong_hieu')->index();

            $collection->text('mo_ta_ngan')->nullable();
            $collection->text('mo_ta_chi_tiet')->nullable();
            $collection->string('link_anh_dai_dien')->nullable();
            $collection->string('trang_thai')->default('active');

            $collection->array('hinh_anh')->nullable();
            $collection->array('thuoc_tinh_chung')->nullable();

            $collection->integer('luot_xem')->default(0);
            $collection->decimal('gia_thap_nhat', 15, 2)->default(0);

            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
