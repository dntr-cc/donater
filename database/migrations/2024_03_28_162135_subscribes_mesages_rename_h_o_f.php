<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscribes_messages', function (Blueprint $table) {
            $table->renameColumn('has_open_fundraisings', 'need_send');
        });
    }

    public function down(): void
    {
        Schema::table('subscribes_messages', function (Blueprint $table) {
            $table->renameColumn('need_send', 'has_open_fundraisings');
        });
    }
};
