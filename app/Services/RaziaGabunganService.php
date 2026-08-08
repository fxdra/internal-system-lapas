<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Str;

class RaziaGabunganService
{
    protected $raziaGabunganWordGeneratorService;
    
    public function __construct(
    RaziaGabunganWordGeneratorService
        $raziaGabunganWordGeneratorService
    )
        {
    $this->raziaGabunganWordGeneratorService =
        $raziaGabunganWordGeneratorService;
        }

    public function getPaginatedKegiatan()
        {
        return DB::table('razia_gabungan_kegiatan')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getById($id)
        {
        return DB::table('razia_gabungan_kegiatan')
            ->where('id', $id)
            ->first();
    }

    public function validateKegiatan($id)
        {
        $kegiatan =
            $this->getById($id);

        if (!$kegiatan) {
            abort(404);
        }

        return $kegiatan;
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

    public function createKegiatan($request)
        {
        return DB::table('razia_gabungan_kegiatan')
            ->insertGetId([

                'tanggal_kegiatan' =>
                    $request->tanggal_kegiatan,

                'jam_mulai' =>
                    $request->jam_mulai,

                'jam_selesai' =>
                    $request->jam_selesai,

                'lokasi' =>
                    $request->lokasi,
                    
                'nomor_surat_dirjen' =>
                    $request->nomor_surat_dirjen,
                
                'tanggal_surat_dirjen' =>
                    $request->tanggal_surat_dirjen,
                    
                'nama_arahan_menteri' =>
                    $request->nama_arahan_menteri,
                
                'tanggal_arahan_menteri' =>
                    $request->tanggal_arahan_menteri,
                
                'nomor_keputusan_menteri' =>
                    $request->nomor_keputusan_menteri,

                'tahun_keputusan_menteri' =>
                    $request->tahun_keputusan_menteri,

                'pimpinan_kegiatan' =>
                    $request->pimpinan_kegiatan,

                'status' => 'draft',

                'created_at' => now(),

                'updated_at' => now(),

            ]);
    }
    
    public function updateKegiatan($id, $request) 
        {
        DB::table('razia_gabungan_kegiatan')
            ->where('id', $id)
            ->update([

                'tanggal_kegiatan' =>
                    $request->tanggal_kegiatan,

                'jam_mulai' =>
                    $request->jam_mulai,

                'jam_selesai' =>
                    $request->jam_selesai,

                'lokasi' =>
                    $request->lokasi,
                    
                'nomor_surat_dirjen' =>
                    $request->nomor_surat_dirjen,
                    
                'tanggal_surat_dirjen' =>
                    $request->tanggal_surat_dirjen,
                    
                'nama_arahan_menteri' =>
                    $request->nama_arahan_menteri,

                'tanggal_arahan_menteri' =>
                    $request->tanggal_arahan_menteri,

                'nomor_keputusan_menteri' =>
                    $request->nomor_keputusan_menteri,

                'tahun_keputusan_menteri' =>
                    $request->tahun_keputusan_menteri,

                'pimpinan_kegiatan' =>
                    $request->pimpinan_kegiatan,

                'updated_at' => now(),

            ]);
    }
    
    public function deleteKegiatan($id)
        {
    $this->ensureDeletable($id);

    $foto =
        DB::table('razia_gabungan_foto')
            ->where('kegiatan_id', $id)
            ->get();

    DB::transaction(function () use ($id) {

        DB::table('razia_gabungan_kamar')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_gabungan_barang')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_gabungan_tes_urine')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_gabungan_foto')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('razia_gabungan_kegiatan')
            ->where('id', $id)
            ->delete();
    });

    foreach ($foto as $item) 
        {
        $path =
            storage_path(
                'app/public/razia-gabungan/' .
                $item->nama_file
            );

        if (file_exists($path)) 
            {
        if (!unlink($path)) {
            logger()->warning('Gagal hapus file razia gabungan: ' . $path);}
            }
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
            ->orderByRaw("
                CAST(
                    REGEXP_SUBSTR(
                        razia_kamar.nama_kamar,
                        '[0-9]+'
                    ) AS UNSIGNED
                )
            ")
            ->get();

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
        $kamarTerpilih =
            DB::table('razia_gabungan_kamar')
                ->where('kegiatan_id', $id)
                ->pluck('kamar_id')
                ->toArray();

        $barangTemuan =
            DB::table('razia_gabungan_barang')
                ->join(
                    'razia_barang',
                    'razia_barang.id',
                    '=',
                    'razia_gabungan_barang.barang_id'
                )
                ->where(
                    'razia_gabungan_barang.kegiatan_id',
                    $id
                )
                ->select(
                    'razia_gabungan_barang.*',
                    'razia_barang.nama_barang',
                    'razia_barang.satuan'
                )
                ->get();

        $tesUrine =
            DB::table('razia_gabungan_tes_urine')
                ->where('kegiatan_id', $id)
                ->get();

        $foto =
            DB::table('razia_gabungan_foto')
                ->where('kegiatan_id', $id)
                ->orderBy('urutan')
                ->get();

        return [

            'kamarTerpilih' =>
                $kamarTerpilih,

            'barangTemuan' =>
                $barangTemuan,

            'tesUrine' =>
                $tesUrine,

            'foto' =>
                $foto

        ];
    }
    
    public function saveKamar($id, $kamarId)
        {
    DB::transaction(function () use (
        $id,
        $kamarId
    ) {

        DB::table('razia_gabungan_kamar')
            ->where('kegiatan_id', $id)
            ->delete();

        if (!$kamarId) {
            return;
        }

        foreach ($kamarId as $item) {

            DB::table('razia_gabungan_kamar')
                ->insert([

                    'kegiatan_id' => $id,

                    'kamar_id' => $item

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

        DB::table('razia_gabungan_barang')
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

            DB::table('razia_gabungan_barang')
                ->insert([

                    'kegiatan_id' => $id,

                    'barang_id' => $item,

                    'jumlah' => $qty

                ]);
        }

    });
}
    
    public function saveTesUrine($id, $kategori, $jumlahPeserta, $hasil)
        {
    DB::transaction(function () use (
        $id,
        $kategori,
        $jumlahPeserta,
        $hasil
    ) {

        DB::table('razia_gabungan_tes_urine')
            ->where('kegiatan_id', $id)
            ->delete();

        if (!$kategori) {
            return;
        }

        foreach ($kategori as $index => $item) {

            DB::table('razia_gabungan_tes_urine')
                ->insert([

                    'kegiatan_id' => $id,

                    'kategori' => $item,

                    'jumlah_peserta' =>
                        $jumlahPeserta[$index] ?? 0,

                    'hasil' =>
                        $hasil[$index] ?? ''

                ]);
        }

    });
}

    public function uploadFoto($id, $file)
        {
        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $namaFile =
            'razia_gabungan_' .
            Str::uuid() .
            '.' .
            $extension;

        $destination =
            storage_path(
                'app/public/razia-gabungan'
            );

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

        DB::table('razia_gabungan_foto')
        ->updateOrInsert(
            ['kegiatan_id' => $id],
                [
                'nama_file' => $namaFile,
                'urutan' => 1
                ]);
        }
    
    public function getKamarRazia($kegiatanId)
        {
    return DB::table('razia_gabungan_kamar')
        ->join(
            'razia_kamar',
            'razia_kamar.id',
            '=',
            'razia_gabungan_kamar.kamar_id'
        )
        ->join(
            'razia_blok',
            'razia_blok.id',
            '=',
            'razia_kamar.blok_id'
        )
        ->where(
            'razia_gabungan_kamar.kegiatan_id',
            $kegiatanId
        )
        ->select(
            'razia_blok.nama_blok',
            'razia_kamar.nama_kamar'
        )
        ->orderBy('razia_blok.nama_blok')
        ->get();
}

    public function getBarangTemuan($kegiatanId)
        {
    return DB::table('razia_gabungan_barang')
        ->join(
            'razia_barang',
            'razia_barang.id',
            '=',
            'razia_gabungan_barang.barang_id'
        )
        ->where(
            'razia_gabungan_barang.kegiatan_id',
            $kegiatanId
        )
        ->select(
            'razia_barang.nama_barang',
            'razia_barang.satuan',
            'razia_gabungan_barang.jumlah'
        )
        ->get();
}

    public function getTesUrine($kegiatanId)
        {
    return DB::table('razia_gabungan_tes_urine')
        ->where(
            'kegiatan_id',
            $kegiatanId
        )
        ->get();
}
    
    public function getFoto($kegiatanId)
        {
    return DB::table('razia_gabungan_foto')
        ->where(
            'razia_gabungan_foto.kegiatan_id',
            $kegiatanId
        )
        ->orderBy('urutan')
        ->pluck('nama_file')
        ->toArray();
        }

    public function getPenandatangan()
        {
    $penandatangan =
        DB::table('razia_penandatangan')
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

        'tes_urine' =>
            $this->getTesUrine($kegiatanId),

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
        
    public function getNarrativeData($id)
        {
    $data =
        $this->getDataLaporan($id);

    $kegiatan =
        $data['kegiatan'];

    $daftarKamar =
        $this->formatDaftarKamar(
            $data['kamar']
    );

    $barangTemuan =
    $this->formatBarangTemuan(
        $data['barang_temuan']
    );

    $tesPegawai =
        collect($data['tes_urine'])
            ->firstWhere('kategori', 'PEGAWAI');

    $tesWbp =
        collect($data['tes_urine'])
            ->firstWhere('kategori', 'WBP');

    return [
        
        'hari_tanggal' =>
            \Carbon\Carbon::parse(
                $kegiatan->tanggal_kegiatan
            )
            ->locale('id')
            ->translatedFormat('l, d F Y'),

        'jam_mulai' =>
            substr(
                $kegiatan->jam_mulai,
                0,
                5
            ),

        'jam_selesai' =>
            substr(
                $kegiatan->jam_selesai,
                0,
                5
            ),

        'lokasi' =>
            $kegiatan->lokasi ?? '-',

        'nama_arahan_menteri' =>
            $kegiatan->nama_arahan_menteri ?? '-',

        'tanggal_arahan_menteri' =>
            $kegiatan->tanggal_arahan_menteri
            ?
            \Carbon\Carbon::parse(
                $kegiatan->tanggal_arahan_menteri
            )
            ->locale('id')
            ->translatedFormat('d F Y')
            :
            '-',

        'nomor_surat_dirjen' =>
            $kegiatan->nomor_surat_dirjen ?? '-',
            
        'tanggal_surat_dirjen' =>
            $kegiatan->tanggal_surat_dirjen
            ?
            \Carbon\Carbon::parse(
                $kegiatan->tanggal_surat_dirjen
            )
            ->locale('id')
            ->translatedFormat('d F Y')
            :
            '-',

        'nomor_keputusan_menteri' =>
            $kegiatan->nomor_keputusan_menteri ?? '-',

        'tahun_keputusan_menteri' =>
            $kegiatan->tahun_keputusan_menteri ?? '-',

        'daftar_kamar' =>
            $daftarKamar,

        'barang_temuan' =>
            $barangTemuan,

        'jumlah_pegawai_tes_urine' =>
            $tesPegawai->jumlah_peserta ?? 0,

        'jumlah_wbp_tes_urine' =>
            $tesWbp->jumlah_peserta ?? 0,

        'hasil_pegawai_tes_urine' =>
            strtolower(
                $tesPegawai->hasil ?? 'negatif'
            ),

        'hasil_wbp_tes_urine' =>
            strtolower(
                $tesWbp->hasil ?? 'negatif'
            ),

        'nama_kplp' =>
            $data['kplp']->nama ?? '-',

        'nama_kasi_kamtib' =>
            $data['kasi_kamtib']->nama ?? '-',

        'nama_kalapas' =>
            $data['kalapas']->nama ?? '-',
            
        'foto' => $data['foto'],

    ];
}

    private function formatBarangTemuan($items)
        {
    if ($items->isEmpty()) {
    return 'nihil';
    }

    $hasil = [];

    foreach ($items as $item) {

        $jumlah =
            (int) $item->jumlah;

        $terbilang =
            strtolower(
                Terbilang::make($jumlah)
            );

        $hasil[] =
            $jumlah .
            ' (' .
            $terbilang .
            ') ' .
            strtolower($item->satuan) .
            ' ' .
            strtolower($item->nama_barang);
    }

    $count =
        count($hasil);

    if ($count === 1) {
        return $hasil[0];
    }

    if ($count === 2) {
        return
            $hasil[0] .
            ' dan ' .
            $hasil[1];
    }

    $last =
        array_pop($hasil);

    return
        implode(', ', $hasil) .
        ', dan ' .
        $last;
}

    private function formatDaftarKamar($kamar)
        {
    $listKamar = [];

    foreach ($kamar as $item) {
        $listKamar[] =
            $item->nama_blok .
            ' ' .
            $item->nama_kamar;
    }

    $count = count($listKamar);

    if ($count === 0) {
        return '-';
    }

    if ($count === 1) {
        return $listKamar[0];
    }

    if ($count === 2) {
        return
            $listKamar[0] .
            ' dan ' .
            $listKamar[1];
    }

    $last =
        array_pop($listKamar);

    return
        implode(', ', $listKamar) .
        ' dan ' .
        $last;
}
    
    public function validateForReport($id)
        {
    $this->validateKegiatan($id);

    $foto =
        DB::table('razia_gabungan_foto')
            ->where('kegiatan_id', $id)
            ->exists();

    if (!$foto) {

        throw new \Exception(
            'Minimal harus ada foto dokumentasi.'
        );
    }

    return true;
}

//--------------------------------------------------------------------------
//NOTE
//--------------------------------------------------------------------------
//Foto lama sengaja tidak dihapus meskipun ada upload baru.
//Tujuannya agar file lama tetap tersedia untuk kebutuhan audit
//atau download ulang laporan yang pernah digenerate.

   public function generateReport($id)
        {
    return DB::transaction(function () use ($id) {

        $this->validateForReport($id);

        $data =
            $this->getNarrativeData($id);

        $foto =
            $this->getFoto($id);

        $data['foto_kolase'] =
            !empty($foto)
            ?
            storage_path(
                'app/public/razia-gabungan/' .
                $foto[0]
            )
            :
            null;

        $tempFile =
            $this->raziaGabunganWordGeneratorService
                ->generate($data);


        $folder =
            storage_path(
                'app/public/laporan-razia-gabungan'
            );

        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }

        $filename =
            'laporan_razia_gabungan_' .
            $id .
            '.docx';

        $finalPath =
            $folder .
            DIRECTORY_SEPARATOR .
            $filename;

        copy($tempFile, $finalPath);

        DB::table('razia_gabungan_kegiatan')
            ->where('id', $id)
            ->update([

                'status' =>
                    'selesai',

                'file_laporan' =>
                    $filename,

                'updated_at' =>
                    now()

            ]);

        return $finalPath;
    });
}
    
   public function download($id)
        {
    $kegiatan =
        $this->validateKegiatan($id);

    if (
        strtolower($kegiatan->status)
        === 'selesai'
    ) {

        if (
            empty($kegiatan->file_laporan)
        ) {
            throw new \Exception(
                'File laporan tidak ditemukan.'
            );
        }

        $path =
            storage_path(
                'app/public/laporan-razia-gabungan/' .
                $kegiatan->file_laporan
            );

        if (!file_exists($path)) {
            throw new \Exception(
                'File laporan hilang dari storage.'
            );
        }

        return $path;
    }

    /*
    |--------------------------------------------------------------------------
    | MASIH DRAFT → GENERATE DULU
    |--------------------------------------------------------------------------
    */
    return $this->generateReport($id);
}

}