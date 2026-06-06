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
        Schema::create('vouchers', function (Blueprint $collection) {
            $collection->string('ma_voucher')->unique();
            $collection->string('ten_voucher')->nullable();
            $collection->string('mo_ta');
            
            // Loại: 'bill' (toàn hóa đơn), 'shipping' (phí vận chuyển)
            $collection->string('loai_voucher')->default('bill'); 
            
            // Hình thức: 'fixed' (số tiền), 'percent' (%)
            $collection->string('hinh_thuc_giam'); 
            $collection->decimal('gia_tri_giam', 15, 2);
            $collection->decimal('muc_giam_toi_da', 15, 2)->nullable();
            
            $collection->decimal('don_hang_toi_thieu', 15, 2)->default(0);
            
            $collection->integer('tong_luot_dung');
            $collection->integer('da_dung')->default(0);
            $collection->dateTime('bat_dau');
            $collection->dateTime('ket_thuc');
            
            $collection->string('trang_thai')->default('active');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
