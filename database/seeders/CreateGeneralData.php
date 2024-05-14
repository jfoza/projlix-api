<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreateGeneralData extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = 'database/seeders/scripts/general-data.sql';
        DB::unprepared(file_get_contents($path));
    }
}
