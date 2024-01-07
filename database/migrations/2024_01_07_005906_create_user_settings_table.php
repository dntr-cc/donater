<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('setting');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->unique(['user_id', 'setting']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
