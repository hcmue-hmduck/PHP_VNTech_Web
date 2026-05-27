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
        Schema::create('flash_sales', function (Blueprint $collection) {
            $collection->string('ma_flash_sales')->unique()->index();
            $collection->string('ten_flash_sales')->nullable();
            $collection->text('mo_ta')->nullable();
            $collection->dateTime('bat_dau')->nullable();
            $collection->dateTime('ket_thuc')->nullable();
            $collection->string('trang_thai')->default('scheduled');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flash_sales');
    }
};
