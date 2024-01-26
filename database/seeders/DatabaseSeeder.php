<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ProductsTableSeeder::class);
        $this->call(ProductSaleSeeder::class);
    }
}