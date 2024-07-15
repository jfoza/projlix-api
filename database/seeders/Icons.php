<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Icons extends Seeder
{
    public function run(): void
    {
        $path = 'database/seeders/scripts/icons.sql';
        DB::unprepared(file_get_contents($path));
    }
}
