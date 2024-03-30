<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('subscribes_trust_codes', function (Blueprint $table) {
            $table->unsignedBigInteger('donate_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('subscribes_trust_codes', function (Blueprint $table) {
            $table->dropColumn('donate_id');
        });
    }
};
