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
        Schema::create('orders', function (Blueprint $collection) {
            $collection->string('ma_don_hang')->unique();
            $collection->string('ma_nguoi_dung')->index();
            $collection->string('ho_ten_nguoi_nhan');
            $collection->string('so_dien_thoai_nhan');
            $collection->text('dia_chi_giao_hang');
            $collection->text('ghi_chu')->nullable();
            $collection->string('ma_voucher')->nullable();
            $collection->decimal('tong_tien_hang', 15, 2);
            $collection->decimal('phi_van_chuyen', 15, 2);
            $collection->decimal('gia_tri_giam_voucher', 15, 2);
            $collection->decimal('tong_thanh_toan', 15, 2);
            $collection->string('phuong_thuc_thanh_toan')->index();
            $collection->string('trang_thai')->default('pending');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
