<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('is_volunteers');
    }

    public function down(): void
    {
        Schema::create('is_volunteers', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->softDeletes();
            $table->timestamps();
        });
    }
};
