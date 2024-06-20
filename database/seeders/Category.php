<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class Category extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            "name" => "Food",
        ]);
        DB::table('categories')->insert([
            "name" => "Clothes",
        ]);
        DB::table('categories')->insert([
            "name" => "Electric",
        ]);
        DB::table('categories')->insert([
            "name" => "Houseware",
        ]);
        DB::table('categories')->insert([
            "name" => "Games",
        ]);
        DB::table('categories')->insert([
            "name" => "Antiques",
        ]);
    }
}
