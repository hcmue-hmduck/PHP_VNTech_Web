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
        Schema::create('banner_images', function (Blueprint $collection) {
            $collection->string('ma_banner')->index();
            $collection->string('image_url');
            $collection->string('tieu_de')->nullable();
            $collection->string('mo_ta')->nullable();
            $collection->string('lien_ket')->nullable();
            $collection->integer('thu_tu_hien_thi')->default(0);
            $collection->boolean('trang_thai')->default(true);
            $collection->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_images');
    }
};
