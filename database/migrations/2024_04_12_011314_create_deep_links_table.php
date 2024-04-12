<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('deep_links', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('volunteer_id')->index();
            $table->string('hash')->unique();
            $table->string('amount');
            $table->time('started_at');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deep_links');
    }
};
