<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscribes', function (Blueprint $table) {
            $table->dropColumn('scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('', function (Blueprint $table) {
            $table->time('scheduled_at')->nullable();;
        });
    }
};
