<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('migrations')->insert([
            'migration' => '2019_12_14_000001_create_personal_access_tokens_table',
            'batch' => 1,
        ]);
    }

    public function down(): void
    {
        DB::table('migrations')->where('migration', '2019_12_14_000001_create_personal_access_tokens_table')->delete();
    }
};
