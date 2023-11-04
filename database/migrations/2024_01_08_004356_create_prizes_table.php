<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prizes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('avatar');
            $table->unsignedBigInteger('fundraising_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('raffle_type');
            $table->integer('raffle_winners');
            $table->float('raffle_price');
            $table->string('available_type')->index();
            $table->json('available_for')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prizes');
    }
};
