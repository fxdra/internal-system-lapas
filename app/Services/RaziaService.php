<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\RaziaNarrativeService;
use App\Services\WordGeneratorService;

class RaziaService
{   
    protected $wordGeneratorService;

    protected $raziaNarrativeService;
    
    public function __construct(
    WordGeneratorService $wordGeneratorService, RaziaNarrativeService $raziaNarrativeService) 
        {
    
    $this->wordGeneratorService =
        $wordGeneratorService;
    
    $this->raziaNarrativeService =
        $raziaNarrativeService;
    
}

    public function validateKegiatan($id)
        {
        $kegiatan = DB::table('razia_kegiatan')
            ->where('id', $id)
            ->first();

        if (!$kegiatan) {

            abort(404);
        }

        return $kegiatan;
    }
    
    public function getPaginatedKegiatan()
        {
    return DB::table('razia_kegiatan')
        ->orderByDesc('id')
        ->paginate(10);
}

    public function ensureEditable($id)
        {
        $kegiatan =
            $this->validateKegiatan($id);

        if (
            strtolower($kegiatan->status)
            === 'selesai'
        ) {

            abort(
                403,
                'Data sudah selesai dan tidak dapat diedit.'
            );
        }
    }

    public function ensureDeletable($id)
        {
        $kegiatan =
            $this->validateKegiatan($id);

        if (
            strtolower($kegiatan->status)
            !== 'draft'
        ) {

            abort(
                403,
                'Hanya data draft yang dapat dihapus.'
            );
        }
    }

    public function getMasterData()
        {
        $blok = DB::table('razia_blok')
            ->orderBy('nama_blok')
            ->get();

        $kamar = DB::table('razia_kamar')
            ->join(
                'razia_blok',
                'razia_blok.id',
                '=',
                'razia_kamar.blok_id'
            )
            ->select(
                'razia_kamar.*',
                'razia_blok.nama_blok'
            )
            ->orderBy('razia_blok.nama_blok')
            ->orderBy('razia_kamar.nama_kamar')
            ->get()
            ->sortBy(function ($item) {

                preg_match(
                    '/^(.*?)(\d+)$/',
                    trim($item->nama_kamar),
                    $match
                );

                $prefix = $match[1] ?? '';

                $number = (int)($match[2] ?? 0);

                return
                    $prefix .
                    '-' .
                    str_pad(
                        $number,
                        5,
                        '0',
                        STR_PAD_LEFT
                    );
            });

        $barang = DB::table('razia_barang')
            ->orderBy('nama_barang')
            ->get();

        return [

            'blok' => $blok,

            'kamar' => $kamar,

            'barang' => $barang

        ];
    }

    public function getDetailData($id)
        {
        return [

            'kamarTerpilih' =>
                DB::table('razia_kegiatan_kamar')
                    ->where('kegiatan_id', $id)
                    ->pluck('kamar_id')
                    ->toArray(),

            'detailKamarTerpilih' =>
                DB::table('razia_kegiatan_kamar')
                    ->join(
                        'razia_kamar',
                        'razia_kamar.id',
                        '=',
                        'razia_kegiatan_kamar.kamar_id'
                    )
                    ->join(
                        'razia_blok',
                        'razia_blok.id',
                        '=',
                        'razia_kamar.blok_id'
                    )
                    ->where(
                        'razia_kegiatan_kamar.kegiatan_id',
                        $id
                    )
                    ->select(
                        'razia_kamar.nama_kamar',
                        'razia_blok.nama_blok'
                    )
                    ->get(),

            'barangTemuan' =>
                DB::table('razia_kegiatan_barang')
                    ->join(
                        'razia_barang',
                        'razia_barang.id',
                        '=',
                        'razia_kegiatan_barang.barang_id'
                    )
                    ->where(
                        'razia_kegiatan_barang.kegiatan_id',
                        $id
                    )
                    ->select(
                        'razia_kegiatan_barang.*',
                        'razia_barang.nama_barang',
                        'razia_barang.satuan'
                    )
                    ->get(),

            'personel' =>
                DB::table('razia_kegiatan_personel')
                    ->where(
                        'razia_kegiatan_personel.kegiatan_id',
                        $id
                    )
                    ->get(),

            'foto' =>
                DB::table('razia_kegiatan_foto')
                    ->where(
                        'razia_kegiatan_foto.kegiatan_id',
                        $id
                    )
                    ->orderBy('urutan')
                    ->get()

        ];
    }

    public function getKamarRazia($kegiatanId)
        {
        return DB::table('razia_kegiatan_kamar')
            ->join(
                'razia_kamar',
                'razia_kamar.id',
                '=',
                'razia_kegiatan_kamar.kamar_id'
            )
            ->join(
                'razia_blok',
                'razia_blok.id',
                '=',
                'razia_kamar.blok_id'
            )
            ->where(
                'razia_kegiatan_kamar.kegiatan_id',
                $kegiatanId
            )
            ->select(
                'razia_kamar.nama_kamar',
                'razia_blok.nama_blok'
            )
            ->get();
    }

    public function getBarangTemuan($kegiatanId)
        {
        return DB::table('razia_kegiatan_barang')
            ->join(
                'razia_barang',
                'razia_barang.id',
                '=',
                'razia_kegiatan_barang.barang_id'
            )
            ->where(
                'razia_kegiatan_barang.kegiatan_id',
                $kegiatanId
            )
            ->select(
                'razia_barang.nama_barang',
                'razia_barang.satuan',
                'razia_kegiatan_barang.jumlah'
            )
            ->get()
            ->map(function ($item) {

                return [

                    'nama_barang' =>
                        $item->nama_barang,

                    'satuan' =>
                        $item->satuan,

                    'jumlah' =>
                        $item->jumlah,

                ];
            })
            ->toArray();
    }

    public function getPersonel($kegiatanId)
        {
        return DB::table('razia_kegiatan_personel')
            ->where(
                'razia_kegiatan_personel.kegiatan_id',
                $kegiatanId
            )
            ->get();
    }

    public function getFoto($kegiatanId)
        {
        return DB::table('razia_kegiatan_foto')
            ->where(
                'razia_kegiatan_foto.kegiatan_id',
                $kegiatanId
            )
            ->orderBy('urutan')
            ->pluck('nama_file')
            ->toArray();
    }

    public function getPenandatangan()
        {
        $penandatangan = DB::table('razia_penandatangan')
            ->where('aktif', 1)
            ->get();

        return [

            'penandatangan' =>
                $penandatangan,

            'kplp' =>
                $penandatangan
                    ->firstWhere(
                        'jenis',
                        'KPLP'
                    ),

            'kasi_kamtib' =>
                $penandatangan
                    ->firstWhere(
                        'jenis',
                        'KASI_KAMTIB'
                    ),

            'kalapas' =>
                $penandatangan
                    ->firstWhere(
                        'jenis',
                        'KALAPAS'
                    )

        ];
    }

    public function getDataLaporan($kegiatanId)
        {
        $kegiatan =
            $this->validateKegiatan($kegiatanId);

        $penandatangan =
            $this->getPenandatangan();

        return [

            'kegiatan' =>
                $kegiatan,

            'kamar' =>
                $this->getKamarRazia($kegiatanId),

            'barang_temuan' =>
                $this->getBarangTemuan($kegiatanId),

            'personel' =>
                $this->getPersonel($kegiatanId),

            'foto' =>
                $this->getFoto($kegiatanId),

            'penandatangan' =>
                $penandatangan['penandatangan'],

            'kplp' =>
                $penandatangan['kplp'],

            'kasi_kamtib' =>
                $penandatangan['kasi_kamtib'],

            'kalapas' =>
                $penandatangan['kalapas']

        ];
    }
    
    public function getNarrativeData($kegiatanId)
        {
    $data =
        $this->getDataLaporan($kegiatanId);

    $staffKplp = 0;

    $reguPengamanan = 0;

    $petugasPiketJabatan =
        'Petugas Piket';

    $petugasPiketJumlah = 0;

    foreach (
        $data['personel']
        as $personel
    ) {

        switch (
            $personel->kategori
        ) {

            case 'STAFF_KPLP':

                $staffKplp =
                    $personel->jumlah;

                break;

            case 'REGU_PENGAMANAN':

                $reguPengamanan =
                    $personel->jumlah;

                break;

            case 'KARUPAM':

                $petugasPiketJabatan =
                    'Karupam';

                $petugasPiketJumlah =
                    $personel->jumlah;

                break;

            case 'WAKARUPAM':

                $petugasPiketJabatan =
                    'Wakarupam';

                $petugasPiketJumlah =
                    $personel->jumlah;

                break;
        }
    }

    return [

        'status' =>
            $data['kegiatan']->status,

        'hari_razia' =>
            \Carbon\Carbon::parse(
                $data['kegiatan']->tanggal_razia
            )
            ->locale('id')
            ->translatedFormat(
                'l, d F Y'
            ),

        'tanggal_razia' =>
            $data['kegiatan']->tanggal_razia,

        'jam_mulai' =>
            $data['kegiatan']->jam_mulai,

        'jam_selesai' =>
            $data['kegiatan']->jam_selesai,

        'lokasi' =>
            $data['kegiatan']->lokasi,

        'pimpinan_razia' =>
            $data['kegiatan']->pimpinan_razia,

        'daftar_blok' =>
            collect($data['kamar'])
                ->pluck('nama_blok')
                ->unique()
                ->values()
                ->toArray(),

        'daftar_kamar' =>
            collect($data['kamar'])
                ->map(function ($kamar) {
                    return trim(
                        $kamar->nama_blok . ' ' . $kamar->nama_kamar
                    );
                })
                ->toArray(),


        'jumlah_kamar' =>
            count(
                $data['kamar']
            ),

        'jumlah_barang_temuan' =>
            count(
                $data['barang_temuan']
            ),

        'jumlah_total_temuan' =>
            collect(
                $data['barang_temuan']
            )->sum('jumlah'),

        'staff_kplp' =>
            $staffKplp,

        'petugas_piket_jabatan' =>
            $petugasPiketJabatan,

        'petugas_piket_jumlah' =>
            $petugasPiketJumlah,

        'regu_pengamanan' =>
            $reguPengamanan,

        'barang_temuan' =>
            $data['barang_temuan'],

        'foto' =>
            $data['foto'],

        'nama_file_laporan' =>

            'Laporan_Atensi_Pimpinan_' .

            $data['kegiatan']->id .

            '_' .

            now()->format(
                'Ymd_His'
            ) .

            '.docx',

        'penandatangan' =>
            $data['penandatangan'],

        'nama_kplp' =>
            $data['kplp']->nama ?? '',

        'nama_kasi_kamtib' =>
            $data['kasi_kamtib']->nama ?? '',

        'nama_kalapas' =>
            $data['kalapas']->nama ?? ''

    ];
}

    public function validateForReport($kegiatanId)
        {
    $data =
        $this->getNarrativeData(
            $kegiatanId
        );

    if (
        empty(
            $data['daftar_kamar']
        )
    ) {

        throw new \Exception(
            'Minimal harus ada kamar yang dirazia'
        );
    }

    if (
        empty(
            $data['foto']
        )
    ) {

        throw new \Exception(
            'Minimal harus ada foto dokumentasi'
        );
    }

    if (
        empty(
            $data['nama_kplp']
        )
    ) {

        throw new \Exception(
            'Penandatangan KPLP belum diatur'
        );
    }

    if (
        empty(
            $data['nama_kasi_kamtib']
        )
    ) {

        throw new \Exception(
            'Penandatangan Kasi Kamtib belum diatur'
        );
    }

    if (
        empty(
            $data['nama_kalapas']
        )
    ) {

        throw new \Exception(
            'Penandatangan Kalapas belum diatur'
        );
    }

    return true;
}
    
    public function createKegiatan($request)
        {
    return DB::table('razia_kegiatan')
        ->insertGetId([

            'tanggal_razia' =>
                $request->tanggal_razia,

            'jam_mulai' =>
                $request->jam_mulai,

            'jam_selesai' =>
                $request->jam_selesai,

            'lokasi' =>
                $request->lokasi,

            'pimpinan_razia' =>
                $request->pimpinan_razia,

            'status' => 'draft',

            'created_at' => now(),

            'updated_at' => now(),

        ]);
}

    public function updateKegiatan($id, $request)
        {
        DB::table('razia_kegiatan')
            ->where('id', $id)
            ->update([

                'tanggal_razia' =>
                    $request->tanggal_razia,

                'jam_mulai' =>
                    $request->jam_mulai,

                'jam_selesai' =>
                    $request->jam_selesai,

                'lokasi' =>
                    $request->lokasi,

                'pimpinan_razia' =>
                    $request->pimpinan_razia,

                'updated_at' => now(),

            ]);
    }

    public function saveKamar($id,array $kamar)
        {
    DB::transaction(function () use (
        $id,
        $kamar
    ) {

        DB::table('razia_kegiatan_kamar')
            ->where('kegiatan_id', $id)
            ->delete();

        foreach ($kamar as $kamarId) {

            DB::table('razia_kegiatan_kamar')
                ->insert([

                    'kegiatan_id' => $id,

                    'kamar_id' => $kamarId

                ]);
        }

    });
}

    public function saveBarang($id, $barangId, $jumlah)
        {
        DB::transaction(function () use (
        $id,
        $barangId,
        $jumlah
        ) {

    DB::table('razia_kegiatan_barang')
        ->where('kegiatan_id', $id)
        ->delete();

    if (!$barangId) {
        return;
    }

    foreach ($barangId as $index => $item) {

        $qty =
            $jumlah[$index] ?? 0;

        if ($qty <= 0) {
            continue;
        }

        DB::table('razia_kegiatan_barang')
            ->insert([

                'kegiatan_id' => $id,

                'barang_id' => $item,

                'jumlah' => $qty

            ]);
    }
});

}

    public function savePersonel($id, $request)
        {
    DB::transaction(function () use (
        $id,
        $request
    ) {

        DB::table('razia_kegiatan_personel')
            ->where('kegiatan_id', $id)
            ->delete();

        $personel = [

            'STAFF_KPLP' =>
                $request->staff_kplp,

            'KARUPAM' =>
                $request->karupam,

            'WAKARUPAM' =>
                $request->wakarupam,

            'REGU_PENGAMANAN' =>
                $request->regu_pengamanan,

        ];

        foreach (
            $personel
            as $kategori => $jumlah
        ) {

            if ($jumlah > 0) {

                DB::table('razia_kegiatan_personel')
                    ->insert([

                        'kegiatan_id' => $id,

                        'kategori' => $kategori,

                        'jumlah' => $jumlah

                    ]);
            }
        }

    });
}

    public function uploadFoto($id, $file)
        {
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $namaFile =
            'razia_' .
            time() .
            '.' .
            $extension;

        $destination =
            storage_path('app/public/razia');

        if (!file_exists($destination)) {

            mkdir(
                $destination,
                0777,
                true
            );
        }

        $file->move(
            $destination,
            $namaFile
        );

        DB::table('razia_kegiatan_foto')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_kegiatan_foto')
            ->insert([

                'kegiatan_id' => $id,

                'nama_file' => $namaFile,

                'urutan' => 1

            ]);
    }
    
    public function deleteKegiatan($id)
        {
    DB::transaction(function () use ($id) {

        $foto = DB::table('razia_kegiatan_foto')
            ->where('kegiatan_id', $id)
            ->get();

        foreach ($foto as $item) {

            $path =
                storage_path(
                    'app/public/razia/' .
                    $item->nama_file
                );

            if (file_exists($path)) {
                unlink($path);
            }
        }

        DB::table('razia_kegiatan_kamar')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_kegiatan_barang')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_kegiatan_personel')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_kegiatan_foto')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_kegiatan')
            ->where('id', $id)
            ->delete();

    });
}
    
    public function markAsFinished($id)
        {
        DB::table('razia_kegiatan')
            ->where('id', $id)
            ->update([

                'status' => 'selesai',

                'updated_at' => now()

            ]);
    }
    
    public function generateReport($id)
        {
        return DB::transaction(function () use ($id) 
        {

        $this->validateKegiatan($id);
    
        $this->validateForReport($id);
    
        $data =
            $this->getNarrativeData($id);
    
        $narasi =
            $this->raziaNarrativeService
                ->generate($data);
    
        $file =
            $this->wordGeneratorService
                ->generate(
                    $data,
                    $narasi
                );
    
        $this->markAsFinished($id);
    
        return $file;
        });

        }

}