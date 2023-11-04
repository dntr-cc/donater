<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::rename('volunteers', 'fundraisings');
    }

    public function down(): void
    {
        Schema::rename('fundraisings', 'volunteers');
    }
};
