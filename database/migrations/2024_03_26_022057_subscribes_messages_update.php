<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscribes_messages', function (Blueprint $table) {
            $table->boolean('has_open_fundraisings')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('subscribes_messages', function (Blueprint $table) {
            $table->boolean('has_open_fundraisings');
        });
    }
};
