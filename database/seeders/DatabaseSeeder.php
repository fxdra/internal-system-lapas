<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Kunjungan::insert([
    [
        'tanggal' => '2026-01-20',
        'sesi' => 'pagi',
        'jam_mulai' => '09:00',
        'jam_selesai' => '12:00',
        'kuota' => 50
    ],
    [
        'tanggal' => '2026-01-20',
        'sesi' => 'siang',
        'jam_mulai' => '13:00',
        'jam_selesai' => '15:00',
        'kuota' => 40
    ]
]);
    }
}
