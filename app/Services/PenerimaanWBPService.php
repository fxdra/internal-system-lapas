<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\PenerimaanWBPWordGeneratorService;
use Riskihajar\Terbilang\Facades\Terbilang;

class PenerimaanWBPService
{   
    protected $penerimaanWBPWordGeneratorService;

    public function __construct(
        PenerimaanWBPWordGeneratorService
            $penerimaanWBPWordGeneratorService
    ) {
            $this->penerimaanWBPWordGeneratorService =
                $penerimaanWBPWordGeneratorService;
    }

    public function getPaginatedKegiatan()
        {
            return DB::table('penerimaan_wbp')
                ->orderByDesc('id')
                ->paginate(10);
        }
        
    public function getById($id)
        {
            return DB::table('penerimaan_wbp')
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
        
    public function createKegiatan($request)
        {
             return DB::table('penerimaan_wbp')
                ->insertGetId([

                'hari_tanggal' =>
                    $request->hari_tanggal,
    
                'jam_mulai' =>
                    $request->jam_mulai,
    
                'lokasi' =>
                    $request->lokasi,
    
                'nomor_surat_dirjenpas' =>
                    $request->nomor_surat_dirjenpas,
    
                'tanggal_surat_dirjenpas' =>
                    $request->tanggal_surat_dirjenpas,
    
                'nama_wbp' =>
                    $request->nama_wbp,
    
                'jumlah_wbp' =>
                    $request->jumlah_wbp,
    
                'asal_upt' =>
                    $request->asal_upt,
    
                'status' => 'draft',
    
                'created_at' => now(),
    
                'updated_at' => now()
            ]);
        }
        
    public function updateKegiatan($id, $request)
        {
            DB::table('penerimaan_wbp')
                ->where('id', $id)
                ->update([

                'hari_tanggal' =>
                    $request->hari_tanggal,
    
                'jam_mulai' =>
                    $request->jam_mulai,
    
                'lokasi' =>
                    $request->lokasi,
    
                'nomor_surat_dirjenpas' =>
                    $request->nomor_surat_dirjenpas,
    
                'tanggal_surat_dirjenpas' =>
                    $request->tanggal_surat_dirjenpas,
    
                'nama_wbp' =>
                    $request->nama_wbp,
    
                'jumlah_wbp' =>
                    $request->jumlah_wbp,
    
                'asal_upt' =>
                    $request->asal_upt,
    
                'updated_at' => now()
            ]);
        }
        
    public function deleteKegiatan($id)
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

    $foto =
        DB::table('penerimaan_wbp_foto')
            ->where('kegiatan_id', $id)
            ->get();

    DB::transaction(function () use ($id) {

        DB::table('penerimaan_wbp_foto')
            ->where('kegiatan_id', $id)
            ->delete();

        DB::table('penerimaan_wbp')
            ->where('id', $id)
            ->delete();
    });

    foreach ($foto as $item) {

        $path =
            storage_path(
                'app/public/penerimaan-wbp/' .
                $item->nama_file
            );

        if (file_exists($path)) {

            if (!unlink($path)) {

                logger()->warning(
                    'Gagal hapus file penerimaan WBP: ' .
                    $path
                );
            }
        }
    }
}
    
    public function uploadFoto($id, $file)
        {
    $this->validateKegiatan($id);

    $oldFoto =
        DB::table('penerimaan_wbp_foto')
            ->where('kegiatan_id', $id)
            ->value('nama_file');

    $extension = strtolower(
        $file->getClientOriginalExtension()
    );

    $namaFile =
        'penerimaan_wbp_' .
        Str::uuid() .
        '.' .
        $extension;

    $destination =
        storage_path(
            'app/public/penerimaan-wbp'
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

    DB::table('penerimaan_wbp_foto')
        ->updateOrInsert(
            [
                'kegiatan_id' => $id
            ],
            [
                'nama_file' => $namaFile,
                'urutan' => 1
            ]
        );

    if ($oldFoto) {

        $oldPath =
            storage_path(
                'app/public/penerimaan-wbp/' .
                $oldFoto
            );

        if (file_exists($oldPath)) {

            if (!unlink($oldPath)) {

                logger()->warning(
                    'Gagal hapus foto lama penerimaan WBP: ' .
                    $oldPath
                );
            }
        }
    }
}
    
    public function getFoto($kegiatanId)
        {
            return DB::table('penerimaan_wbp_foto')
                ->where('kegiatan_id', $kegiatanId)
                ->value('nama_file');
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

    return [

        'id' => $id,

        'hari_tanggal' =>
            \Carbon\Carbon::parse(
                $kegiatan->hari_tanggal
            )
            ->locale('id')
            ->translatedFormat('l, d F Y'),

        'jam_mulai' =>
            substr(
                $kegiatan->jam_mulai,
                0,
                5
            ),

        'lokasi' =>
            $kegiatan->lokasi ?? '-',

        'nomor_surat_dirjenpas' =>
            $kegiatan->nomor_surat_dirjenpas ?? '-',

        'tanggal_surat_dirjenpas' =>
            $kegiatan->tanggal_surat_dirjenpas
            ?
            \Carbon\Carbon::parse(
                $kegiatan->tanggal_surat_dirjenpas
            )
            ->locale('id')
            ->translatedFormat('d F Y')
            :
            '-',

        'nama_wbp' =>
            $kegiatan->nama_wbp ?? '-',

        'jumlah_wbp' =>
            ($kegiatan->jumlah_wbp ?? 1)
            . ' ('
            . strtolower(
                \Riskihajar\Terbilang\Facades\Terbilang::make(
                    $kegiatan->jumlah_wbp ?? 1
                )
            )
            . ')',

        'asal_upt' =>
            $kegiatan->asal_upt ?? '-',

        'nama_kplp' =>
            $data['kplp']->nama ?? '-',

        'nama_kasi_kamtib' =>
            $data['kasi_kamtib']->nama ?? '-',

        'nama_kalapas' =>
            $data['kalapas']->nama ?? '-',

        'foto' =>
            $data['foto']

    ];
}
    
    public function validateForReport($id)
        {
    $kegiatan =
        $this->validateKegiatan($id);

    if (empty($kegiatan->hari_tanggal)) {
        throw new \Exception(
            'Hari/Tanggal kegiatan wajib diisi.'
        );
    }

    if (empty($kegiatan->nama_wbp)) {
        throw new \Exception(
            'Nama WBP wajib diisi.'
        );
    }

    if (empty($kegiatan->asal_upt)) {
        throw new \Exception(
            'Asal UPT wajib diisi.'
        );
    }

    $foto =
        DB::table('penerimaan_wbp_foto')
            ->where('kegiatan_id', $id)
            ->exists();

    if (!$foto) {
        throw new \Exception(
            'Harus ada foto dokumentasi.'
        );
    }

    return true;
}
        
    public function markAsFinished($id, $fileLaporan)
        {
    DB::table('penerimaan_wbp')
        ->where('id', $id)
        ->update([

            'status' => 'selesai',

            'file_laporan' =>
                $fileLaporan,

            'updated_at' => now()

        ]);
}
        
    public function generateReport($id)
        {
    $kegiatan =
        $this->validateKegiatan($id);

    if (
        strtolower($kegiatan->status) === 'selesai' &&
        !empty($kegiatan->file_laporan)
    ) {
        return storage_path(
            'app/public/penerimaan-wbp/laporan/' .
            $kegiatan->file_laporan
        );
    }

    $this->validateForReport($id);

    $data =
        $this->getNarrativeData($id);

    $foto =
        $this->getFoto($id);

    $data['foto_kolase'] =
        $foto
        ? storage_path(
            'app/public/penerimaan-wbp/' .
            $foto
        )
        : null;

    $file =
        $this->penerimaanWBPWordGeneratorService
            ->generate($data);

    $fileName =
        basename($file);

    DB::transaction(function () use ($id, $fileName) {

        $this->markAsFinished(
            $id,
            $fileName
        );

    });

    return $file;
}

}