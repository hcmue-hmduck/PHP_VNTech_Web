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
        Schema::create('review_replies', function (Blueprint $collection) {
            $collection->string('ma_phan_hoi')->nullable()->unique();
            $collection->string('ma_danh_gia')->index();
            $collection->string('ma_admin')->index();
            $collection->text('noi_dung');
            $collection->array('lich_su_phan_hoi')->nullable();
            $collection->boolean('is_updated')->default(false);
            $collection->string('trang_thai')->default('active');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_replies');
    }
};
