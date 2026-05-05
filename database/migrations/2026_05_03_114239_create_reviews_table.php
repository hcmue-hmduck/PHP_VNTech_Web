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
        Schema::create('reviews', function (Blueprint $collection) {
            $collection->string('ma_san_pham')->index();
            $collection->string('ma_nguoi_dung')->index();
            $collection->string('ma_don_hang')->index(); // Để xác thực là đã mua hàng mới được đánh giá
            
            $collection->integer('so_sao')->default(5);
            $collection->text('noi_dung');
            $collection->array('hinh_anh')->nullable();
            
            $collection->string('trang_thai')->default('pending'); // pending, approved, hidden
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
