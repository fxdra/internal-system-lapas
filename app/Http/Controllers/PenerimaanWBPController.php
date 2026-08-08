<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PenerimaanWBPService;

class PenerimaanWBPController extends Controller
{
    protected $penerimaanWBPService;

    public function __construct(
        PenerimaanWBPService $penerimaanWBPService
    ) {
        $this->penerimaanWBPService =
            $penerimaanWBPService;
    }

    public function index()
    {
        $kegiatan =
            $this->penerimaanWBPService
                ->getPaginatedKegiatan();

        return view(
            'admin-banceuy.indexpenerimaanwbp',
            compact('kegiatan')
        );
    }

    public function create()
    {
        return view(
            'admin-banceuy.createpenerimaanwbp'
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari_tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'lokasi' => 'required|string|max:255',
            'nomor_surat_dirjenpas' => 'nullable|string|max:255',
            'tanggal_surat_dirjenpas' => 'nullable|date',
            'nama_wbp' => 'required|string|max:255',
            'jumlah_wbp' => 'required|integer|min:1',
            'asal_upt' => 'required|string|max:255',
        ]);

        $id =
            $this->penerimaanWBPService
                ->createKegiatan($request);

        return redirect()
            ->route(
                'penerimaanwbp.edit',
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
            $this->penerimaanWBPService
                ->validateKegiatan($id);

        $this->penerimaanWBPService
            ->ensureEditable($id);

        $foto =
            $this->penerimaanWBPService
                ->getFoto($id);

        return view(
            'admin-banceuy.editpenerimaanwbp',
            compact('kegiatan', 'foto')
        );
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'hari_tanggal' => 'required|date',
            'jam_mulai' => 'required',
            'lokasi' => 'required|string|max:255',
            'nomor_surat_dirjenpas' => 'nullable|string|max:255',
            'tanggal_surat_dirjenpas' => 'nullable|date',
            'nama_wbp' => 'required|string|max:255',
            'jumlah_wbp' => 'required|integer|min:1',
            'asal_upt' => 'required|string|max:255',
        ]);

        $this->penerimaanWBPService
            ->ensureEditable($id);

        $this->penerimaanWBPService
            ->updateKegiatan($id, $request);

        return back()->with(
            'success',
            'Data kegiatan berhasil diperbarui.'
        );
    }

    public function uploadFoto(Request $request, $id)
    {
        $request->validate([
            'foto' =>
                'required|image|mimes:jpg,jpeg,png|max:5120'
        ]);

        $this->penerimaanWBPService
            ->ensureEditable($id);

        $this->penerimaanWBPService
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
            $this->penerimaanWBPService
                ->getDataLaporan($id);

        $narrative =
            $this->penerimaanWBPService
                ->getNarrativeData($id);

        return view(
            'admin-banceuy.previewpenerimaanwbp',
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
            $this->penerimaanWBPService
                ->generateReport($id);

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
            'Gagal download laporan.'
        );
    }
}

    public function destroy($id)
    {
        $this->penerimaanWBPService
            ->deleteKegiatan($id);

        return redirect()
            ->route('penerimaanwbp.index')
            ->with(
                'success',
                'Data draft berhasil dihapus.'
            );
    }
}