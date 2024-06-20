<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
  
        DB::table('admins')->insert([
            'username' => "israa@gmail.com",
            'password' => Hash::make('123123123'),
            'email' => "israa@gmail.com",
        ]);
        DB::table('admins')->insert([
            'username' => "majid@gmail.com",
            'password' => Hash::make('123123123'),
            'email' => "majid@gmail.com",
        ]);
        DB::table('users')->insert([
            'first_name' => "israa",
            'last_name' => "omran",
            'phone_number' => "2113",
            'gender' => "female",
            'email' => "israa@gmail.com",
            'password' => Hash::make('123123123'),
            'role' => "superadmin",
        ]);
        DB::table('users')->insert([
            'first_name' => "mhd",
            'last_name' => "majid",
            'phone_number' => "2123",
            'gender' => "male",
            'email' => "majid@gmail.com",
            'password' => Hash::make('123123123'),
            'role' => "admin",
        ]);
        DB::table('users')->insert([
            'first_name' => "user",
            'last_name' => "user",
            'phone_number' => "3213",
            'gender' => "male",
            'email' => "user@gmail.com",
            'password' => Hash::make('123123123'),
            'role' => "user",
        ]);
        DB::table('users')->insert([
            'first_name' => "user1",
            'last_name' => "user1",
            'phone_number' => "2143",
            'gender' => "male",
            'email' => "user1@gmail.com",
            'password' => Hash::make('123123123'),
            'role' => "user",
        ]);
        DB::table('users')->insert([
            'first_name' => "user2",
            'last_name' => "user2",
            'phone_number' => "2153",
            'gender' => "male",
            'email' => "user2@gmail.com",
            'password' => Hash::make('123123123'),
            'role' => "user",
        ]);
        DB::table('users')->insert([
            'first_name' => "user3",
            'last_name' => "user3",
            'phone_number' => "6653",
            'gender' => "male",
            'email' => "user3@gmail.com",
            'password' => Hash::make('123123123'),
            'role' => "user",
        ]);
    }
}
