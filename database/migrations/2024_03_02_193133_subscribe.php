<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->time('scheduled_at')->change()->nullable();
            $table->timestamp('first_message_at')->nullable();
            $table->string('frequency')->default('daily');
        });
    }

    public function down(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->dropColumn('first_message_at');
            $table->dropColumn('frequency');
        });
    }
};
