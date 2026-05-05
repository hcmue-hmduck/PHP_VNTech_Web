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
            $collection->string('ma_san_pham')->index(); // Link tới Product cha
            $collection->string('ma_sku')->unique(); // SKU định danh duy nhất (Ví dụ: IP15-BLU-256)
            
            // Giá và Kho
            $collection->decimal('gia_ban', 15, 2);
            $collection->decimal('gia_niem_yet', 15, 2)->nullable(); // Giá trước khi giảm
            $collection->integer('so_luong_ton_kho')->default(0);
            
            // Thuộc tính (Dùng mảng hoặc object trong MongoDB rất linh hoạt)
            $collection->array('thuoc_tinh'); // Ví dụ: ['color' => 'Xanh', 'size' => 'L']
            
            // Ảnh riêng cho biến thể (Nếu có)
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
