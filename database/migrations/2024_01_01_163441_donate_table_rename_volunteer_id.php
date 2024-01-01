<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('alter table donates rename column volunteer_id to fundraising_id;');
    }

    public function down(): void
    {
        DB::statement('alter table donates rename column fundraising_id to volunteer_id;');
    }
};
