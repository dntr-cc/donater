<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('available_for');
            $table->string('available_status')->default('new');
        });
    }

    public function down(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->json('available_for')->nullable();
            $table->dropColumn('available_status');
        });
    }
};
