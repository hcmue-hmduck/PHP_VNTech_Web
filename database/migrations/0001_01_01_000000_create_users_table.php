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
        Schema::create('users', function (Blueprint $collection) {
            $collection->string('ho_ten');
            $collection->string('email')->unique(); 
            
            $collection->string('so_dien_thoai')->nullable();
            $collection->string('password')->nullable();

            $collection->string('vai_tro')->default('user')->index();
            
            $collection->string('avatar_url')->nullable();
            $collection->text('bio')->nullable();
            $collection->string('trang_thai')->default('active')->index(); // active, inactive, banned
            
            $collection->timestamp('email_verified_at')->nullable();
            
            $collection->rememberToken();
            $collection->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $collection) {
            $collection->string('email')->primary();
            $collection->string('token');
            $collection->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $collection) {
            $collection->string('id')->unique();
            $collection->string('user_id')->nullable()->index();
            $collection->string('ip_address', 45)->nullable();
            $collection->text('user_agent')->nullable();
            $collection->longText('payload');
            $collection->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
