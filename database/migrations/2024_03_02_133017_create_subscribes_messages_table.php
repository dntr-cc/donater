<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscribes_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscribes_id')->index();
            $table->string('frequency')->default('daily');
            $table->timestamp('schedule_at')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscribes_messages');
    }
};
