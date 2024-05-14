<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = 'database/migrations/scripts/2024_04_04_191726_create_db_structure.sql';
        DB::unprepared(file_get_contents($path));
    }
};
