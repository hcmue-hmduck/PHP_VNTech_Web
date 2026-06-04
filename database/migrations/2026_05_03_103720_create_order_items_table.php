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
        Schema::create('order_items', function (Blueprint $collection) {
            $collection->string('ma_chi_tiet_don_hang')->nullable()->index();
            $collection->string('ma_don_hang')->index();
            $collection->string('ma_bien_the')->index();
            $collection->string('ten_san_pham');
            $collection->string('ten_bien_the');
            $collection->string('hinh_anh');
            $collection->integer('so_luong');
            $collection->decimal('gia_niem_yet', 15, 2);
            $collection->decimal('don_gia', 15, 2);
            $collection->string('ma_bien_the_flash_sale')->nullable();
            $collection->decimal('so_tien_giam_flash_sale', 15, 2)->default(0);
            $collection->decimal('thanh_tien', 15, 2);

            $collection->unique(['ma_don_hang', 'ma_bien_the']);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
