<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Throwable;

class SyncKalenderLibur extends Command
{
    protected $signature = 'kalender:sync-libur {tahun?}';

    protected $description = 'Sinkronisasi kalender libur dari API Refnusa';

    public function handle(): int
    {
        $tahun = (int) ($this->argument('tahun') ?? now()->year);

        if ($tahun < 2020 || $tahun > 2100) {
            $this->error('Tahun harus berada antara 2020 dan 2100.');

            return self::FAILURE;
        }

        try {
            $response = Http::acceptJson()
                ->connectTimeout(5)
                ->timeout(20)
                ->get('https://refnusa.dev/v1/holidays', [
                    'year' => $tahun,
                ]);

            if (! $response->successful()) {
                $this->error(
                    'API Refnusa mengembalikan HTTP ' .
                        $response->status()
                );

                return self::FAILURE;
            }

            $data = $response->json();

            if (
                ! is_array($data) ||
                ($data['year'] ?? null) != $tahun ||
                ! isset($data['holidays']) ||
                ! is_array($data['holidays']) ||
                count($data['holidays']) === 0
            ) {
                $this->error('Respons API tidak valid atau daftar libur kosong.');

                return self::FAILURE;
            }

            $baris = [];
            $tanggalTerlihat = [];

            foreach ($data['holidays'] as $libur) {
                if (
                    ! is_array($libur) ||
                    ! isset($libur['date'], $libur['name'], $libur['type']) ||
                    ! is_string($libur['date']) ||
                    ! is_string($libur['name']) ||
                    ! is_string($libur['type'])
                ) {
                    $this->error('Ada data libur dengan format tidak valid.');

                    return self::FAILURE;
                }

                $tanggal = $libur['date'];
                $nama = trim($libur['name']);

                $jenis = match ($libur['type']) {
                    'libur_nasional' => 'Libur Nasional',
                    'cuti_bersama' => 'Cuti Bersama',
                    default => null,
                };

                $tanggalValid = \DateTimeImmutable::createFromFormat(
                    '!Y-m-d',
                    $tanggal
                );

                if (
                    ! $tanggalValid ||
                    $tanggalValid->format('Y-m-d') !== $tanggal ||
                    (int) $tanggalValid->format('Y') !== $tahun ||
                    $nama === '' ||
                    mb_strlen($nama) > 150 ||
                    $jenis === null
                ) {
                    $this->error("Data libur tidak valid: {$tanggal}");

                    return self::FAILURE;
                }

                if (isset($tanggalTerlihat[$tanggal])) {
                    $this->error("Tanggal duplikat dalam respons API: {$tanggal}");

                    return self::FAILURE;
                }

                $tanggalTerlihat[$tanggal] = true;

                $baris[] = [
                    'tanggal' => $tanggal,
                    'nama' => $nama,
                    'jenis' => $jenis,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::transaction(function () use ($baris): void {
                DB::table('kalender_libur')->upsert(
                    $baris,
                    ['tanggal'],
                    ['nama', 'jenis', 'updated_at']
                );
            });

            $this->info(
                "Sinkronisasi tahun {$tahun} berhasil. " .
                    count($baris) . ' tanggal diproses.'
            );

            return self::SUCCESS;
        } catch (ConnectionException $e) {
            report($e);
            $this->error('Tidak dapat terhubung ke API Refnusa.');

            return self::FAILURE;
        } catch (Throwable $e) {
            report($e);
            $this->error('Sinkronisasi gagal. Periksa log aplikasi.');

            return self::FAILURE;
        }
    }
}
