<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscribes_trust_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('hash')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribes_trust_codes');
    }
};
