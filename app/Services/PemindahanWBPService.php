<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Riskihajar\Terbilang\Facades\Terbilang;

class PemindahanWBPService
{
    protected $pemindahanWBPWordGeneratorService;

    public function __construct(
        PemindahanWBPWordGeneratorService
            $pemindahanWBPWordGeneratorService
    ) {
        $this->pemindahanWBPWordGeneratorService =
            $pemindahanWBPWordGeneratorService;
    }

    public function getPaginatedKegiatan()
    {
        return DB::table('pemindahan_wbp')
            ->orderByDesc('id')
            ->paginate(10);
    }

    public function getById($id)
    {
        return DB::table('pemindahan_wbp')
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
        return DB::table('pemindahan_wbp')
            ->insertGetId([

                'hari_tanggal' =>
                    $request->hari_tanggal,

                'jam_mulai' =>
                    $request->jam_mulai,

                'jam_tiba' =>
                    $request->jam_tiba,

                'lokasi' =>
                    $request->lokasi,

                'lokasi_tujuan' =>
                    $request->lokasi_tujuan,

                'nomor_surat_izin' =>
                    $request->nomor_surat_izin,

                'nomor_surat_persetujuan' =>
                    $request->nomor_surat_persetujuan,

                'nama_wbp' =>
                    $request->nama_wbp,

                'jumlah_wbp' =>
                    $request->jumlah_wbp,

                'jumlah_petugas' =>
                    $request->jumlah_petugas,

                'jumlah_polisi' =>
                    $request->jumlah_polisi,

                'total_update_wbp' =>
                    $request->total_update_wbp,

                'status' => 'draft',

                'created_at' => now(),

                'updated_at' => now()
            ]);
    }

    public function updateKegiatan($id, $request)
    {
        DB::table('pemindahan_wbp')
            ->where('id', $id)
            ->update([

                'hari_tanggal' =>
                    $request->hari_tanggal,

                'jam_mulai' =>
                    $request->jam_mulai,

                'jam_tiba' =>
                    $request->jam_tiba,

                'lokasi' =>
                    $request->lokasi,

                'lokasi_tujuan' =>
                    $request->lokasi_tujuan,

                'nomor_surat_izin' =>
                    $request->nomor_surat_izin,

                'nomor_surat_persetujuan' =>
                    $request->nomor_surat_persetujuan,

                'nama_wbp' =>
                    $request->nama_wbp,

                'jumlah_wbp' =>
                    $request->jumlah_wbp,

                'jumlah_petugas' =>
                    $request->jumlah_petugas,

                'jumlah_polisi' =>
                    $request->jumlah_polisi,

                'total_update_wbp' =>
                    $request->total_update_wbp,

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
            DB::table('pemindahan_wbp_foto')
                ->where('kegiatan_id', $id)
                ->get();

        DB::transaction(function () use ($id) {

            DB::table('pemindahan_wbp_foto')
                ->where('kegiatan_id', $id)
                ->delete();

            DB::table('pemindahan_wbp')
                ->where('id', $id)
                ->delete();
        });

        foreach ($foto as $item) {

            $path =
                storage_path(
                    'app/public/pemindahan-wbp/' .
                    $item->nama_file
                );

            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }

    public function uploadFoto($id, $file)
    {
        $this->validateKegiatan($id);

        $oldFoto =
            DB::table('pemindahan_wbp_foto')
                ->where('kegiatan_id', $id)
                ->value('nama_file');

        $extension = strtolower(
            $file->getClientOriginalExtension()
        );

        $namaFile =
            'pemindahan_wbp_' .
            Str::uuid() .
            '.' .
            $extension;

        $destination =
            storage_path(
                'app/public/pemindahan-wbp'
            );

        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        $file->move(
            $destination,
            $namaFile
        );

        DB::table('pemindahan_wbp_foto')
            ->updateOrInsert(
                ['kegiatan_id' => $id],
                [
                    'nama_file' => $namaFile,
                    'urutan' => 1
                ]
            );

        if ($oldFoto) {

            $oldPath =
                storage_path(
                    'app/public/pemindahan-wbp/' .
                    $oldFoto
                );

            if (file_exists($oldPath)) {
                @unlink($oldPath);
            }
        }
    }

    public function getFoto($kegiatanId)
    {
        return DB::table('pemindahan_wbp_foto')
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
                    ->firstWhere('jenis', 'KPLP'),

            'kasi_kamtib' =>
                $penandatangan
                    ->firstWhere('jenis', 'KASI_KAMTIB'),

            'kalapas' =>
                $penandatangan
                    ->firstWhere('jenis', 'KALAPAS')
        ];
    }

    public function getDataLaporan($kegiatanId)
    {
        $kegiatan =
            $this->validateKegiatan($kegiatanId);

        $penandatangan =
            $this->getPenandatangan();

        return [

            'kegiatan' => $kegiatan,

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
                substr($kegiatan->jam_mulai, 0, 5),

            'jam_tiba' =>
                substr($kegiatan->jam_tiba, 0, 5),

            'lokasi' =>
                $kegiatan->lokasi ?? '-',

            'lokasi_tujuan' =>
                $kegiatan->lokasi_tujuan ?? '-',

            'nomor_surat_izin' =>
                $kegiatan->nomor_surat_izin ?? '-',

            'nomor_surat_persetujuan' =>
                $kegiatan->nomor_surat_persetujuan ?? '-',

            'nama_wbp' =>
                $kegiatan->nama_wbp ?? '-',

            'jumlah_wbp' =>
                ($kegiatan->jumlah_wbp ?? 1)
                . ' ('
                . strtolower(
                    Terbilang::make(
                        $kegiatan->jumlah_wbp ?? 1
                    )
                )
                . ')',

            'jumlah_petugas' =>
                ($kegiatan->jumlah_petugas ?? 1)
                . ' ('
                . strtolower(
                    Terbilang::make(
                        $kegiatan->jumlah_petugas ?? 1
                    )
                )
                . ')',

            'jumlah_polisi' =>
                ($kegiatan->jumlah_polisi ?? 1)
                . ' ('
                . strtolower(
                    Terbilang::make(
                        $kegiatan->jumlah_polisi ?? 1
                    )
                )
                . ')',

            'total_update_wbp' =>
                ($kegiatan->total_update_wbp ?? 1)
                . ' ('
                . strtolower(
                    Terbilang::make(
                        $kegiatan->total_update_wbp ?? 1
                    )
                )
                . ')',

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
        $this->validateKegiatan($id);

        $foto =
            DB::table('pemindahan_wbp_foto')
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
        DB::table('pemindahan_wbp')
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
            strtolower($kegiatan->status)
            === 'selesai' &&
            !empty($kegiatan->file_laporan)
        ) {
            return storage_path(
                'app/public/pemindahan-wbp/laporan/' .
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
                'app/public/pemindahan-wbp/' .
                $foto
            )
            : null;

        $file =
            $this->pemindahanWBPWordGeneratorService
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