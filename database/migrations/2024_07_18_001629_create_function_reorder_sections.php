<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $path = 'database/migrations/scripts/2024_07_18_001629_create_function_reorder_sections.sql';
        DB::unprepared(file_get_contents($path));
    }
};
