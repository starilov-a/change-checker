<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SiteTableSeeder::class);
        $this->command->info('Таблица сайтов загружена данными!');
        $this->call(PageTableSeeder::class);
        $this->command->info('Таблица страниц загружена данными!');
        $this->call(ChangeTableSeeder::class);
        $this->command->info('Таблица изменеий загружена данными!');
    }
}
