<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('donates', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('fundraising_id');
        });
        Schema::table('fundraisings', function (Blueprint $table) {
            $table->index('user_id');
        });
        Schema::table('user_settings', function (Blueprint $table) {
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::table('donates', function (Blueprint $table) {
            $table->dropIndex('user_id');
            $table->dropIndex('fundraising_id');
        });
        Schema::table('fundraisings', function (Blueprint $table) {
            $table->index('user_id');
        });
        Schema::table('user_settings', function (Blueprint $table) {
            $table->dropIndex('user_id');
        });
    }
};
