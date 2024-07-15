<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Colors extends Seeder
{
    public function run(): void
    {
        $path = 'database/seeders/scripts/colors.sql';
        DB::unprepared(file_get_contents($path));
    }
}
