<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{DB, Hash};

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('admins')->insert([
            'id' => '1',
            'nama' => 'fiqih',
            'nip' => '123456789',
            'password' => Hash::make('password123'),
            'role' => 'superadmin',
        ]);
    }
}
