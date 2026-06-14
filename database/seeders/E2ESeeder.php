<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class E2ESeeder extends Seeder
{
    public function run(): void
    {
        $this->call(DiarySeeder::class);
    }
}
