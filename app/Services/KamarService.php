<?php

namespace App\Services;

use Illuminate\Support\Collection;

class KamarService
{

    private const SPECIAL_ROOM_CAPACITY = [

        'C-12' => 7,

        'D-12' => 7,

        'E-1'  => 7,

        'F-1'  => 7,

    ];

    private const ROOM_RULES = [

        'A' => [
            'db_blok' => 'A',
            'start'   => 1,
            'end'     => 12,
        ],

        'B' => [
            'db_blok' => 'B',
            'start'   => 1,
            'end'     => 12,
        ],

        'C' => [
            'db_blok' => 'C',
            'start'   => 1,
            'end'     => 12,
        ],

        'D' => [
            'db_blok' => 'D',
            'start'   => 1,
            'end'     => 12,
        ],

        'E' => [
            'db_blok' => 'E',
            'start'   => 1,
            'end'     => 9,
        ],

        'F' => [
            'db_blok' => 'F',
            'start'   => 1,
            'end'     => 9,
        ],

        'G' => [
            'db_blok' => 'G',
            'start'   => 1,
            'end'     => 10,
        ],

        'H' => [
            'db_blok' => 'H',
            'start'   => 1,
            'end'     => 12,
        ],

        'SEL ISOLASI' => [
            'db_blok' => 'ISOLASI',
            'start'   => 1,
            'end'     => 10,
        ],

        'DAPUR' => [
            'db_blok' => 'DAPUR',
            'start'   => 1,
            'end'     => 1,
        ],
    ];

    private const BLOCK_INFO = [

        'Blok A' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok B' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok C' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok D' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok E' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok F' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok G' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'Blok H' => [
            'icon' => '📍',
            'type' => 'hunian',
        ],

        'RUMAH SAKIT' => [
            'icon' => '🏥',
            'type' => 'virtual',
        ],

        'DAPUR' => [
            'icon' => '🍳',
            'type' => 'khusus',
        ],

        'SEL ISOLASI' => [
            'icon' => '🔒',
            'type' => 'isolasi',
        ],

    ];

    public static function blockName(string $blok): string
    {
        return strtoupper(trim($blok));
    }

    public static function convertSdpRoom(string $kodeBlok, string $lokasiSel): array
    {
        $kodeBlok  = strtoupper(trim($kodeBlok));
        $lokasiSel = strtoupper(trim($lokasiSel));

        $rule = self::ROOM_RULES[$kodeBlok] ?? null;

        if (!$rule) {
            throw new \Exception("Blok {$kodeBlok} tidak dikenali.");
        }

        if (!preg_match('/\/(\d+)$/', $lokasiSel, $match)) {
            throw new \Exception("Format lokasi SDP tidak valid: {$lokasiSel}");
        }

        $nomorKamar = (int) $match[1];

        if ($nomorKamar < $rule['start'] || $nomorKamar > $rule['end']) {
            throw new \Exception(
                "Nomor kamar {$nomorKamar} tidak sesuai untuk Blok {$kodeBlok}."
            );
        }

        return [
            'kode_blok'       => $rule['db_blok'],
            'lokasi_sel'      => 'KAMAR ' . $nomorKamar,
            'lokasi_sel_asli' => $lokasiSel,
        ];
    }

    public static function make($data): array
    {
        $blok = strtoupper(trim(
            is_array($data)
                ? ($data['lokasi_blok'] ?? '')
                : ($data->lokasi_blok ?? '')
        ));

        $sel = trim(
            is_array($data)
                ? ($data['lokasi_sel'] ?? '')
                : ($data->lokasi_sel ?? '')
        );

        $kodeBlok = strtoupper(trim(
            is_array($data)
                ? ($data['kode_blok'] ?? '')
                : ($data->kode_blok ?? '')
        ));

        /*
    |--------------------------------------------------------------------------
    | BLOK HUNIAN A - H
    |--------------------------------------------------------------------------
    */

        if (preg_match('/^[A-H]$/', $kodeBlok)) {

            preg_match('/(\d+)$/', $sel, $match);

            $nomorKamar = isset($match[1])
                ? (int) $match[1]
                : null;

            return [
                'letter' => $kodeBlok,

                'block' => 'Blok ' . $kodeBlok,

                'room' => $nomorKamar,

                'display' => $nomorKamar !== null
                    ? 'Blok ' . $kodeBlok . ' - ' . $nomorKamar
                    : 'Blok ' . $kodeBlok,

                'sort_key' => (($kodeBlok >= 'A' && $kodeBlok <= 'H')
                    ? ((ord($kodeBlok) - 64) * 100)
                    : 999900
                ) + ($nomorKamar ?? 99),
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | UNIT KHUSUS
    |--------------------------------------------------------------------------
    */

        preg_match('/(\d+)$/', $sel, $match);

        $nomorKamar = isset($match[1])
            ? (int) $match[1]
            : null;

        return [
            'letter' => null,

            'block' => $blok,

            'room' => $nomorKamar,

            'display' => $nomorKamar !== null
                ? $blok . ' - Kamar ' . $nomorKamar
                : $blok,

            'sort_key' => match ($blok) {
                'DAPUR' => 900100,
                'SEL ISOLASI' => 900200 + ($nomorKamar ?? 99),
                default => 999999,
            },
        ];
    }

    public static function blockInfo(string $block): array
    {
        return self::BLOCK_INFO[$block] ?? [
            'icon' => '📍',
            'type' => 'lainnya',
        ];
    }

    public static function kapasitasPerKamar(string $kodeBlok, ?int $noKamar): int
    {
        $kodeBlok = strtoupper(trim($kodeBlok));

        // =========================
        // KAMAR KHUSUS
        // =========================

        if ($noKamar !== null) {

            $key = "{$kodeBlok}-{$noKamar}";

            if (isset(self::SPECIAL_ROOM_CAPACITY[$key])) {
                return self::SPECIAL_ROOM_CAPACITY[$key];
            }
        }

        // =========================
        // BLOK HUNIAN
        // =========================

        if (preg_match('/^[A-H]$/', $kodeBlok)) {
            return 14;
        }

        // =========================
        // UNIT KHUSUS
        // =========================

        return match ($kodeBlok) {

            'DAPUR'   => 14,

            'ISOLASI' => 1,

            'RUMAH SAKIT' => 2,

            default => 0,
        };
    }

    public static function sort(Collection $items): Collection
    {
        return $items
            ->sortBy(fn($item) => self::make($item)['sort_key'])
            ->values();
    }

    public static function buildRoom($kamar): object
    {
        $info = self::make($kamar);

        $room = $info['room'];

        // Khusus Sel Isolasi
        if ($room === null && strtoupper($kamar->lokasi_blok) === 'SEL ISOLASI') {

            preg_match('/\/(\d+)$/', $kamar->lokasi_sel, $match);

            $room = $match[1] ?? null;
        }

        $displayBlock = $info['block'];
        $kapasitas = self::kapasitasPerKamar(
            $kamar->kode_blok,
            $room
        );
        $jumlah = $kamar->wbps->count();
        $persen = $kapasitas > 0
            ? round(($jumlah / $kapasitas) * 100)
            : 0;

        return (object)[

            'kamar_id'      => $kamar->id,
            'kode_kamar'    => $kamar->kode_kamar,
            'kode_blok'     => $kamar->kode_blok,
            'nama_kamar'    => $info['display'],
            'lokasi_blok'   => $displayBlock,
            'lokasi_blok_asli' => $kamar->lokasi_blok,
            'lokasi_sel'    => $room
                ? 'Kamar ' . $room
                : $kamar->lokasi_sel,
            'no_kamar'      => $room,
            'status_kamar'  => $kamar->status_kamar,
            'kapasitas'     => $kapasitas,
            'jumlah_wbp'    => $jumlah,
            'persen'        => $persen,
            'badge'         => self::buildBadge($persen),
            'wbps'          => $kamar->wbps->map(function ($wbp) {

                return [

                    'id' => $wbp->id,
                    'nama' => $wbp->nama,
                    'no_reg_instansi' => $wbp->no_reg_instansi,
                    'agama' => $wbp->agama,
                    'jenis_kejahatan' => $wbp->jenis_kejahatan,
                    'putusan' => $wbp->putusan,
                    'ekspirasi' => $wbp->ekspirasi,
                    'lokasi_blok' => $wbp->lokasi_blok,
                    'lokasi_sel' => $wbp->lokasi_sel,
                    'status_wbp' => $wbp->status_wbp,
                    'kamar_id' => $wbp->kamar_id,
                    'foto_wbp' => $wbp->foto_wbp,
                ];
            })->values()
        ];
    }

    public static function buildData(Collection $kamars): Collection
    {
        return $kamars
            ->map(function ($kamar) {

                return self::buildRoom($kamar);
            })
            ->sortBy(function ($item) {

                return sprintf(
                    '%s-%03d',
                    $item->lokasi_blok,
                    $item->no_kamar
                );
            })
            ->values();
    }

    public static function buildGrouped(Collection $data): Collection
    {
        return $data
            ->groupBy('lokasi_blok')
            ->sortKeys();
    }

    public static function buildSections(Collection $grouped): array
    {
        $hunian = collect();
        $khusus = collect();

        foreach ($grouped as $blok => $kamars) {

            if (in_array($blok, [
                'RUMAH SAKIT',
                'DAPUR',
                'SEL ISOLASI'
            ])) {

                $khusus[$blok] = $kamars;
            } else {

                $hunian[$blok] = $kamars;
            }
        }

        return [

            '🏢 BLOK HUNIAN' => $hunian,

            '🏥 UNIT KHUSUS' => $khusus,

        ];
    }

    public static function buildSummary(Collection $grouped): Collection
    {
        return $grouped->map(function ($kamars, $blok) {

            $jumlahKamar = $kamars->count();

            $jumlahWbp = $kamars->sum(function ($kamar) {
                return $kamar->jumlah_wbp;
            });

            $kapasitas = $kamars->sum(function ($kamar) {

                return self::kapasitasPerKamar(
                    $kamar->kode_blok,
                    $kamar->no_kamar
                );
            });

            $selisih = $kapasitas - $jumlahWbp;

            $sisa = max(0, $selisih);

            $over = abs(min(0, $selisih));

            $persen = $kapasitas > 0
                ? round(($jumlahWbp / $kapasitas) * 100)
                : 0;

            if ($persen > 100) {

                $warna = 'danger';
                $status = 'Over Kapasitas';
            } elseif ($persen === 100) {

                $warna = 'danger';
                $status = 'Penuh';
            } elseif ($persen >= 95) {

                $warna = 'danger';
                $status = 'Kritis';
            } elseif ($persen >= 85) {

                $warna = 'orange';
                $status = 'Hampir Penuh';
            } elseif ($persen >= 70) {

                $warna = 'warning';
                $status = 'Padat';
            } else {

                $warna = 'success';
                $status = 'Normal';
            }

            return [

                'blok' => $blok,

                'jumlah_kamar' => $jumlahKamar,

                'jumlah_wbp' => $jumlahWbp,

                'kapasitas' => $kapasitas,

                'sisa' => $sisa,

                'over' => $over,

                'persen' => $persen,

                'warna' => $warna,

                'status' => $status,

            ];
        });
    }

    public static function buildOperationalAlerts(
        Collection $summary,
        int $totalWbpBon,
        int $totalWbpSakit
    ): Collection {
        $alerts = collect();

        foreach ($summary as $item) {

            if ($item['persen'] < 70) {
                continue;
            }

            $level = match (true) {

                $item['persen'] > 100 => 'danger',

                $item['persen'] >= 95 => 'danger',

                $item['persen'] >= 85 => 'orange',

                default => 'warning',
            };

            $priority = match (true) {

                $item['persen'] > 100 => 1,

                $item['persen'] >= 95 => 1,

                $item['persen'] >= 85 => 2,

                default => 3,
            };

            $message = $item['over'] > 0
                ? "Hunian {$item['jumlah_wbp']} dari kapasitas {$item['kapasitas']} WBP · Lebih {$item['over']} WBP"
                : "Hunian {$item['jumlah_wbp']} dari kapasitas {$item['kapasitas']} WBP · Sisa {$item['sisa']}";

            $alerts->push([

                'type' => 'occupancy',

                'level' => $level,

                'title' => "{$item['blok']} {$item['status']}",

                'message' => $message,

                'priority' => $priority,

            ]);
        }


        $totalWbpLuarTembok = $totalWbpBon + $totalWbpSakit;

        if ($totalWbpLuarTembok > 0) {

            $alerts->push([

                'type' => 'outside',

                'level' => 'info',

                'title' => "{$totalWbpLuarTembok} WBP di Luar Tembok",

                'message' => "{$totalWbpBon} BON · {$totalWbpSakit} Sakit",

                'priority' => 4,

            ]);
        }

        return $alerts
            ->sortBy('priority')
            ->values();
    }

    private static function buildBadge(int $persen): string
    {
        if ($persen >= 100) {

            return 'bg-danger';
        }

        if ($persen >= 90) {

            return 'bg-warning text-dark';
        }

        return 'bg-success';
    }

    public static function buildPrint(Collection $kamars)
    {
        return self::sort($kamars)

            ->groupBy(function ($kamar) {

                return self::make($kamar)['letter'];
            })

            ->map(function ($items) {

                return $items->map(function ($kamar) {

                    $info = self::make($kamar);

                    return (object)[

                        'kamar_id' => $kamar->id,
                        'kode_kamar' => $kamar->kode_kamar,
                        'lokasi_blok' => $info['block'],
                        'lokasi_sel' => 'Kamar ' . $info['room'],
                        'nama_kamar' => $info['display'],
                        'no_kamar' => $info['room'],
                        'img_barcode' => $kamar->img_barcode,

                        'kapasitas' => self::kapasitasPerKamar(
                            $kamar->kode_blok,
                            $info['room']
                        ),
                    ];
                });
            });
    }

    public static function buildPrintData(Collection $kamars): array
    {
        $data = self::buildData($kamars);

        $grouped = self::buildGrouped($data);

        return [

            'data' => $data,

            'grouped' => $grouped,

            'summary' => self::buildSummary($grouped),

            'sections' => self::buildSections($grouped),

        ];
    }

    public static function query($query)
    {
        return $query

            ->orderByRaw("
                CASE

                    WHEN lokasi_blok='ARGAPURA'
                    AND lokasi_sel LIKE 'TP Umum LT 1/%'
                    THEN 1

                    WHEN lokasi_blok='ARGAPURA'
                    AND lokasi_sel LIKE 'TP Isolasi LT 2/%'
                    THEN 2

                    WHEN lokasi_blok='BROMO'
                    AND lokasi_sel LIKE 'TP Umum LT 1/%'
                    THEN 3

                    WHEN lokasi_blok='BROMO'
                    AND lokasi_sel LIKE 'TP Umum LT 2/%'
                    THEN 4

                    WHEN lokasi_blok='CEREMAI'
                    AND lokasi_sel LIKE 'TP Umum LT 1/%'
                    THEN 5

                    WHEN lokasi_blok='CEREMAI'
                    AND lokasi_sel LIKE 'TP Umum LT 2/%'
                    THEN 6

                    WHEN lokasi_blok='DIENG'
                    AND lokasi_sel LIKE 'TP Umum LT 1/%'
                    THEN 7

                    WHEN lokasi_blok='DIENG'
                    AND lokasi_sel LIKE 'TP Umum LT 2/%'
                    THEN 8

                    ELSE 99

                END
            ")

            ->orderByRaw("
                CAST(
                    SUBSTRING_INDEX(lokasi_sel,'/',-1)
                AS UNSIGNED)
            ");
    }

    public static function buildView(Collection $kamars): array
    {
        return self::buildPrintData($kamars);
    }

    public static function buildIndex($query)
    {
        return self::query($query)
            ->paginate(10)
            ->withQueryString();
    }
}
