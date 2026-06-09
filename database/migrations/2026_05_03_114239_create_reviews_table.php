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
            $collection->string('ma_danh_gia')->nullable()->unique();
            $collection->string('ma_san_pham')->index();
            $collection->string('ma_bien_the')->index();
            $collection->string('ma_nguoi_dung')->index();
            $collection->string('ma_don_hang')->index(); // Để xác thực là đã mua hàng mới được đánh giá
            $collection->string('ma_chi_tiet_don_hang')->index();
            
            $collection->string('ten_bien_the')->nullable();
            $collection->integer('so_sao')->default(5);
            $collection->text('noi_dung');
            $collection->array('danh_sach_anh')->nullable();
            $collection->array('video')->nullable();
            
            $collection->boolean('is_anonymous')->default(false);
            $collection->string('trang_thai')->default('active'); // active, hidden

            $collection->array('lich_su_chinh_sua')->nullable();
            
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
