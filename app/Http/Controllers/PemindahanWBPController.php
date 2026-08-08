<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PemindahanWBPService;

class PemindahanWBPController extends Controller
{
    protected $pemindahanWBPService;

    public function __construct(
        PemindahanWBPService
            $pemindahanWBPService
    ) {
        $this->pemindahanWBPService =
            $pemindahanWBPService;
    }

    public function index()
    {
        $kegiatan =
            $this->pemindahanWBPService
                ->getPaginatedKegiatan();

        return view(
            'admin-banceuy.indexpemindahanwbp',
            compact('kegiatan')
        );
    }

    public function create()
    {
        return view(
            'admin-banceuy.createpemindahanwbp'
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'hari_tanggal' =>
                'required|date',

            'jam_mulai' =>
                'required',

            'jam_tiba' =>
                'required',

            'lokasi' =>
                'required|string|max:255',

            'lokasi_tujuan' =>
                'required|string|max:255',

            'nomor_surat_izin' =>
                'required|string|max:255',

            'nomor_surat_persetujuan' =>
                'required|string|max:255',

            'nama_wbp' =>
                'required|string',

            'jumlah_wbp' =>
                'required|integer|min:1',

            'jumlah_petugas' =>
                'required|integer|min:1',

            'jumlah_polisi' =>
                'required|integer|min:1',

            'total_update_wbp' =>
                'required|integer|min:1',

        ]);

        $id =
            $this->pemindahanWBPService
                ->createKegiatan($request);

        return redirect()
            ->route(
                'pemindahanwbp.edit',
                $id
            )
            ->with(
                'success',
                'Data kegiatan berhasil disimpan.'
            );
    }

    public function edit($id)
    {
        $kegiatan =
            $this->pemindahanWBPService
                ->validateKegiatan($id);

        $this->pemindahanWBPService
            ->ensureEditable($id);

        $foto =
            $this->pemindahanWBPService
                ->getFoto($id);

        return view(
            'admin-banceuy.editpemindahanwbp',
            compact(
                'kegiatan',
                'foto'
            )
        );
    }

    public function update(
        Request $request,
        $id
    ) {
        $request->validate([

            'hari_tanggal' =>
                'required|date',

            'jam_mulai' =>
                'required',

            'jam_tiba' =>
                'required',

            'lokasi' =>
                'required|string|max:255',

            'lokasi_tujuan' =>
                'required|string|max:255',

            'nomor_surat_izin' =>
                'required|string|max:255',

            'nomor_surat_persetujuan' =>
                'required|string|max:255',

            'nama_wbp' =>
                'required|string',

            'jumlah_wbp' =>
                'required|integer|min:1',

            'jumlah_petugas' =>
                'required|integer|min:1',

            'jumlah_polisi' =>
                'required|integer|min:1',

            'total_update_wbp' =>
                'required|integer|min:1',

        ]);

        $this->pemindahanWBPService
            ->ensureEditable($id);

        $this->pemindahanWBPService
            ->updateKegiatan(
                $id,
                $request
            );

        return back()->with(
            'success',
            'Data kegiatan berhasil diperbarui.'
        );
    }

    public function uploadFoto(
        Request $request,
        $id
    ) {
        $request->validate([
            'foto' =>
                'required|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $this->pemindahanWBPService
            ->ensureEditable($id);

        $this->pemindahanWBPService
            ->uploadFoto(
                $id,
                $request->file('foto')
            );

        return back()->with(
            'success',
            'Foto berhasil diupload.'
        );
    }

    public function preview($id)
    {
        $data =
            $this->pemindahanWBPService
                ->getDataLaporan($id);

        $narrative =
            $this->pemindahanWBPService
                ->getNarrativeData($id);

        return view(
            'admin-banceuy.previewpemindahanwbp',
            compact(
                'data',
                'narrative'
            )
        );
    }

    public function download($id)
    {
        try {

            $file =
                $this->pemindahanWBPService
                    ->generateReport($id);

            if (!file_exists($file)) {
                throw new \Exception(
                    'File laporan tidak ditemukan.'
                );
            }

            return response()->download(
                $file,
                basename($file),
                [
                    'Content-Type' =>
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ]
            );

        } catch (\Exception $e) {

            logger()->error($e);

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function destroy($id)
    {
        $this->pemindahanWBPService
            ->deleteKegiatan($id);

        return redirect()
            ->route(
                'pemindahanwbp.index'
            )
            ->with(
                'success',
                'Data draft berhasil dihapus.'
            );
    }
}