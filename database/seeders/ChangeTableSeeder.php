<?php

namespace Database\Seeders;

use App\Models\Change;
use Illuminate\Database\Seeder;

class ChangeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Change::factory()->count(2)->create();
    }
}
