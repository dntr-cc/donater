<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('fundraisings', function (Blueprint $table) {
            $table->boolean('forget')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('fundraisings', function (Blueprint $table) {
            $table->dropColumn('forget');
        });
    }
};
