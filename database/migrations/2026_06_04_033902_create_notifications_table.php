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
        Schema::create('notifications', function (Blueprint $collection) {
            $collection->string('ma_thong_bao')->index();
            $collection->string('ma_nguoi_dung');
            $collection->string('tieu_de');
            $collection->string('noi_dung');
            $collection->string('loai');
            $collection->string('duong_dan');
            $collection->boolean('da_doc');
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification');
    }
};
