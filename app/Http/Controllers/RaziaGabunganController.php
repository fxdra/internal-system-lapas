<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RaziaGabunganService;

class RaziaGabunganController extends Controller
{
    protected $raziaGabunganService;

    public function __construct(
        RaziaGabunganService $raziaGabunganService)
        {
        $this->raziaGabunganService =
            $raziaGabunganService;
    }

    public function index()
        {
        $kegiatan =
            $this->raziaGabunganService
                ->getPaginatedKegiatan();

        return view(    
            'admin-banceuy.indexraziagabungan',
            compact('kegiatan')
        );
    }

    public function create()
        {
        return view(
            'admin-banceuy.createraziagabungan'
        );
    }

    public function store(Request $request)
        {
        $request->validate([

            'tanggal_kegiatan' => 'required|date',

            'jam_mulai' => 'required',

            'jam_selesai' => 'required',

            'lokasi' => 'required|string|max:255',

            'pimpinan_kegiatan' => 'required|string|max:255',

        ]);

        $id =
            $this->raziaGabunganService
                ->createKegiatan($request);

        return redirect()
            ->route(
                'raziagabungan.edit',
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
            $this->raziaGabunganService
                ->validateKegiatan($id);

        $this->raziaGabunganService
            ->ensureEditable($id);

        $masterData =
            $this->raziaGabunganService
                ->getMasterData();

        $detailData =
            $this->raziaGabunganService
                ->getDetailData($id);

        return view(
            'admin-banceuy.editraziagabungan',
            array_merge(
                compact('kegiatan'), $masterData, $detailData));
        }

    public function update(Request $request, $id)
        {
        $request->validate([

            'tanggal_kegiatan' => 'required|date',

            'jam_mulai' => 'required',

            'jam_selesai' => 'required',

            'lokasi' => 'required|string|max:255',

            'pimpinan_kegiatan' => 'required|string|max:255',

        ]);

        $this->raziaGabunganService
            ->ensureEditable($id);

        $this->raziaGabunganService
            ->updateKegiatan(
                $id,
                $request
            );

        return back()->with(
            'success',
            'Data kegiatan berhasil diperbarui.'
        );
    }
    
    public function saveKamar(Request $request, $id)
        {
    $this->raziaGabunganService
        ->ensureEditable($id);

    $this->raziaGabunganService
        ->saveKamar(
            $id,
            $request->kamar_id
        );

    return back()->with(
        'success',
        'Data kamar razia berhasil disimpan.'
    );
}

    public function saveBarang(Request $request,$id)
        {
        $this->raziaGabunganService
            ->ensureEditable($id);

        $this->raziaGabunganService
            ->saveBarang(
                $id,
                $request->barang_id,
                $request->jumlah
            );

        return back()->with(
            'success',
            'Barang temuan berhasil disimpan.'
        );
    }

    public function saveTesUrine(Request $request,$id)
        {
        $this->raziaGabunganService
            ->ensureEditable($id);

        $this->raziaGabunganService
            ->saveTesUrine(
                $id,
                $request->kategori,
                $request->jumlah_peserta,
                $request->hasil
            );

        return back()->with(
            'success',
            'Data tes urine berhasil disimpan.'
        );
    }

    public function uploadFoto(Request $request,$id)
        {
        $request->validate([

            'foto' =>
                'required|image|mimes:jpg,jpeg,png|max:5120'

        ]);

        $this->raziaGabunganService
            ->ensureEditable($id);

        $this->raziaGabunganService
            ->uploadFoto(
                $id,
                $request->file('foto')
            );

        return back()->with(
            'success',
            'Foto berhasil diupload.'
        );
    }

    public function destroy($id)
        {
    $this->raziaGabunganService
        ->deleteKegiatan($id);

    return redirect()
        ->route('raziagabungan.index')
        ->with(
            'success',
            'Data draft berhasil dihapus.'
        );
}

    public function preview($id)
        {
    $data =
        $this->raziaGabunganService
            ->getDataLaporan($id);

    $narrative =
        $this->raziaGabunganService
            ->getNarrativeData($id);

    return view(
        'admin-banceuy.previewraziagabungan',
        compact('data', 'narrative')
    );
}

    public function download($id)
        {
    try {

        $file =
            $this->raziaGabunganService
                ->download($id);

        return response()->download(
            $file,
            basename($file)
        );

    } catch (\Throwable $e) {

        return back()->with(
            'error',
            $e->getMessage()
        );
    }
}
}