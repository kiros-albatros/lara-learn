<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@vsekolesa.ru',
            'password' => Hash::make('admin'),
            'role' => 'admin',
            'created_at' => DB::raw('NOW()'),
        ]);
        DB::table('users')->insertGetId([
            'name' => 'Сергей Продажи',
            'email' => 'sales@vsekolesa.ru',
            'password' => Hash::make('sales'),
            'role' => 'staff',
            'created_at' => DB::raw('NOW()'),
        ]);
        DB::table('users')->insertGetId([
            'name' => 'Ирина Контент',
            'email' => 'content@vsekolesa.ru',
            'password' => Hash::make('content'),
            'role' => 'staff',
            'created_at' => DB::raw('NOW()'),
        ]);
        DB::table('users')->insertGetId([
            'name' => 'Марк Программирование',
            'email' => 'development@vsekolesa.ru',
            'password' => Hash::make('development'),
            'role' => 'staff',
            'created_at' => DB::raw('NOW()'),
        ]);
    }
}
