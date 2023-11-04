<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement(<<<SQL
            alter table donates drop column validated_at;
        SQL);
        DB::statement(<<<SQL
            alter table donates drop constraint donates_uniq_hash_unique;
        SQL);
        DB::statement(<<<SQL
            alter table donates rename column uniq_hash to hash;
        SQL);
        DB::statement(<<<SQL
            create index donates_hash_index
                on donates (hash);
        SQL);
    }

    public function down(): void
    {
        DB::statement(<<<SQL
            alter table donates add column validated_at timestamp default null;
        SQL);
        DB::statement(<<<SQL
            drop index  donates_uniq_hash_unique;
        SQL);
        DB::statement(<<<SQL
            alter table donates rename column hash to uniq_hash;
        SQL);
        DB::statement(<<<SQL
            create unique index donates_uniq_hash_unique
                on donates (uniq_hash);
        SQL);
    }
};
