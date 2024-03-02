<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscribes_messages', function (Blueprint $table) {
            $table->renameColumn('schedule_at', 'scheduled_at');
        });
    }

    public function down(): void
    {
        Schema::table('subscribes_messages', function (Blueprint $table) {
            $table->renameColumn('scheduled_at', 'schedule_at');
        });
    }
};
