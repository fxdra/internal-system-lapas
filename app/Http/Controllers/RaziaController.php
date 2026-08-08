<?php

namespace App\Http\Controllers;

use App\Services\RaziaService;
use App\Services\WordGeneratorService;
use App\Services\RaziaNarrativeService;
use Illuminate\Http\Request;

class RaziaController extends Controller
{
    protected $raziaService;
    
    public function __construct(
        RaziaService $raziaService,
        WordGeneratorService $wordGeneratorService,
        RaziaNarrativeService $narrativeService) 
        {
        $this->raziaService = $raziaService;
        $this->wordGeneratorService = $wordGeneratorService;
        $this->narrativeService = $narrativeService;
    }
    
    public function index()
        {
    $kegiatan =
        $this->raziaService
            ->getPaginatedKegiatan();

    return view(
        'admin-banceuy.indexrazia',
        compact('kegiatan')
    );
}

    public function create()
        {
    $masterData =
        $this->raziaService
            ->getMasterData();

    return view(
        'admin-banceuy.createrazia',
        $masterData
    );
}

    public function edit($id)
        {
    $kegiatan =
        $this->raziaService
            ->validateKegiatan($id);

    $this->raziaService
        ->ensureEditable($id);

    $masterData =
        $this->raziaService
            ->getMasterData();

    $detailData =
        $this->raziaService
            ->getDetailData($id);

    return view(
        'admin-banceuy.editrazia',
        array_merge(
            compact('kegiatan'),
            $masterData,
            $detailData
        )
    );
}
    
    public function store(Request $request)
        {
    $request->validate([

        'tanggal_razia' => 'required|date',

        'jam_mulai' => 'required',

        'jam_selesai' => 'required',

        'lokasi' => 'required|string|max:255',

        'pimpinan_razia' => 'required|string|max:255',

    ]);

    $id =
        $this->raziaService
            ->createKegiatan($request);

    return redirect()
        ->route('razia.edit', $id)
        ->with(
            'success',
            'Data berhasil disimpan'
        );
}
    
    public function update(Request $request, $id)
        {
    $request->validate([

        'tanggal_razia' => 'required|date',

        'jam_mulai' => 'required',

        'jam_selesai' => 'required',

        'lokasi' => 'required|string|max:255',

        'pimpinan_razia' => 'required|string|max:255',

    ]);

    $this->raziaService
        ->ensureEditable($id);

    $this->raziaService
        ->updateKegiatan(
            $id,
            $request
        );

    return redirect()
        ->route('razia.edit', $id)
        ->with(
            'success',
            'Data berhasil diperbarui'
        );
}
    
    public function destroy($id)
        {
    $this->raziaService
        ->ensureDeletable($id);

    $this->raziaService
        ->deleteKegiatan($id);

    return redirect()
        ->route('razia.index')
        ->with(
            'success',
            'Data draft berhasil dihapus.'
        );
}
    
    public function saveKamar(Request $request, $id)
        {
    $request->validate([
        'kamar' => 'required|array|min:1'
    ]);

    $this->raziaService
        ->ensureEditable($id);

    $this->raziaService
        ->saveKamar(
            $id,
            $request->kamar
        );

    return back()->with(
        'success',
        'Data kamar berhasil disimpan.'
    );
}
        
    public function saveBarang(Request $request, $id)
        {
    $this->raziaService
        ->ensureEditable($id);

    $this->raziaService
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
        
    public function savePersonel(Request $request, $id)
        {
    $this->raziaService
        ->ensureEditable($id);

    $this->raziaService
        ->savePersonel(
            $id,
            $request
        );
        
    $request->validate([
    'staff_kplp' => 'nullable|integer|min:0',
    'karupam' => 'nullable|integer|min:0',
    'wakarupam' => 'nullable|integer|min:0',
    'regu_pengamanan' => 'nullable|integer|min:0',
    ]);

    return back()->with(
        'success',
        'Data personel berhasil disimpan.'
    );
}
        
    public function uploadFoto(Request $request, $id)
        {
    $request->validate([

        'foto' =>
            'required|image|mimes:jpg,jpeg,png|max:5120'

    ]);

    $this->raziaService
        ->ensureEditable($id);

    $this->raziaService
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
        $this->raziaService
            ->getNarrativeData($id);

    $data['id'] = $id;

    return view(
        'admin-banceuy.previewrazia',
        compact('data')
    );
}

    public function download($id)
        {
        try {
    
            $file =
                $this->raziaService
                    ->generateReport($id);
    
            return response()->download(
                $file,
                basename($file)
            );
    
        } catch (\Exception $e) {
    
            return back()->with(
                'error',
                $e->getMessage()
            );
        }
}

}