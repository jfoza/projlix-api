<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $path = 'database/migrations/scripts/2100_12_10_113051_base_structure_profiles_rules.sql';
        DB::unprepared(file_get_contents($path));
    }
};
