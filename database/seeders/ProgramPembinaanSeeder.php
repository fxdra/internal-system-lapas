<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramPembinaanSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $programs = [
            [
                'kategori' => 'Kepribadian',
                'nama_program' => 'Santri',
            ],
            [
                'kategori' => 'Kepribadian',
                'nama_program' => 'Pramuka',
            ],
            [
                'kategori' => 'Kepribadian',
                'nama_program' => 'Sanggar Kegiatan Belajar (SKB)',
            ],
            [
                'kategori' => 'Kemandirian',
                'nama_program' => 'Tataboga',
            ],
            [
                'kategori' => 'Kemandirian',
                'nama_program' => 'Barbershop',
            ],
            [
                'kategori' => 'Kemandirian',
                'nama_program' => 'Budidaya',
            ],
        ];

        foreach ($programs as $program) {
            DB::table('program_pembinaan')->insertOrIgnore([
                ...$program,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
