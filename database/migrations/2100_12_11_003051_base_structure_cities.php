<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $path = 'database/migrations/scripts/2100_12_11_003051_base_structure_cities.sql';
        DB::unprepared(file_get_contents($path));
    }
};
