<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // relasi ke user
            $table->string('nama_toko');
            $table->string('slug')->unique();
            $table->text('deskripsi')->nullable();
            $table->string('logo')->nullable();
            $table->string('telepon')->nullable(); // nomor HP
            $table->boolean('telepon_aktif')->default(false); // tampil di halaman toko?
            $table->timestamps();
            $table->string('image_public_id')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tokos');
    }
};