<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('session_id')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            $table->string('path', 500);
            $table->string('method', 10);

            $table->string('referer', 500)->nullable();

            $table->timestamp('visited_at')->useCurrent();

            $table->index('user_id');
            $table->index('session_id');
            $table->index('visited_at');
            $table->index('path');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};