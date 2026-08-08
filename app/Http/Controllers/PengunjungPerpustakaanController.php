<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\KategoriBuku;
use App\Models\PengunjungPerpustakaan;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PengunjungPerpustakaanController extends Controller
{
    // Halaman utama
    public function index()
    {
        $pengunjungs = PengunjungPerpustakaan::orderBy('created_at', 'desc')->with('buku')->paginate(10);
        $bukus = Buku::where('status', 'TERSEDIA')->get();
        return view('admin-banceuy.pengunjung-perpustakaan', compact('pengunjungs', 'bukus'));
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'buku_id' => 'required|exists:buku,id',
            'nama_peminjam' => 'required|string|max:255',
            'kamar_sel' => 'required|string|max:50',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:PEMINJAMAN,PENGEMBALIAN',
            'foto_buku' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status_barcode' => 'required|in:VALID,EXPIRED',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $data = $request->only([
            'buku_id',
            'nama_peminjam',
            'kamar_sel',
            'tanggal_pinjam',
            'tanggal_kembali',
            'status',
            'status_barcode'
        ]);

        // Uppercase otomatis
        $data['nama_peminjam'] = strtoupper($data['nama_peminjam']);
        $data['kamar_sel'] = strtoupper($data['kamar_sel']);

        // Kurangi stock buku jika status PEMINJAMAN
        $buku = Buku::findOrFail($data['buku_id']);
        if ($data['status'] === 'PEMINJAMAN') {
            if ($buku->stock <= 0) {
                return redirect()->back()->with('error', 'Stock buku tidak cukup');
            }
            $buku->decrement('stock');
        }

        // Upload foto buku jika ada
        if ($request->hasFile('foto_buku')) {
            $data['foto_buku'] = $request->file('foto_buku')->store('pengunjung/foto', 'public');
        }

        // Generate unique id_barcode
        $uuid = Str::uuid()->toString();
        $data['id_barcode'] = $uuid;

        // Generate QR Code image
        $qrCode = new QrCode($uuid);
        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrPath = 'pengunjung/barcode/' . $uuid . '.png';
        Storage::disk('public')->put($qrPath, $result->getString());
        $data['img_barcode'] = $qrPath;

        // Simpan ke database
        PengunjungPerpustakaan::create($data);

        return redirect()->back()->with('success', 'Data berhasil ditambahkan');
    }

    // Update data
    public function update(Request $request, $id)
    {
        $pengunjung = PengunjungPerpustakaan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'buku_id' => 'required|exists:buku,id',
            'nama_peminjam' => 'required|string|max:255',
            'kamar_sel' => 'required|string|max:50',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:PEMINJAMAN,PENGEMBALIAN',
            'status_barcode' => 'required|in:VALID,EXPIRED',
            'foto_buku' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $oldStatus = $pengunjung->status;
        $oldBukuId = $pengunjung->buku_id;

        $data = $request->only([
            'buku_id',
            'nama_peminjam',
            'kamar_sel',
            'tanggal_pinjam',
            'tanggal_kembali',
            'status',
            'status_barcode'
        ]);

        $data['nama_peminjam'] = strtoupper($data['nama_peminjam']);
        $data['kamar_sel'] = strtoupper($data['kamar_sel']);

        // Update stock otomatis
        $oldBuku = Buku::findOrFail($oldBukuId);
        $newBuku = Buku::findOrFail($data['buku_id']);

        if ($oldStatus == 'PEMINJAMAN' && $data['status'] == 'PENGEMBALIAN') {
            $oldBuku->increment('stock');
        }

        if ($oldBukuId != $data['buku_id'] && $data['status'] == 'PEMINJAMAN') {
            if ($newBuku->stock <= 0) {
                return redirect()->back()->with('error', 'Stock buku tidak cukup');
            }
            $newBuku->decrement('stock');
        }

        // Upload foto buku
        if ($request->hasFile('foto_buku')) {
            if ($pengunjung->foto_buku) Storage::disk('public')->delete($pengunjung->foto_buku);
            $data['foto_buku'] = $request->file('foto_buku')->store('pengunjung/foto', 'public');
        }

        $pengunjung->update($data);

        return redirect()->back()->with('success', 'Data berhasil diupdate');
    }


    public function show($id)
    {
        $peminjaman = PengunjungPerpustakaan::with('buku')->findOrFail($id);
        return view('admin-banceuy.detail_peminjaman', compact('peminjaman'));
    }

    // Hapus data
    public function destroy($id)
    {
        $pengunjung = PengunjungPerpustakaan::findOrFail($id);

        // Normalisasi status supaya pengecekan aman
        $status = strtoupper(trim($pengunjung->status));

        if ($status === 'PEMINJAMAN') {
            return response()->json([
                'success' => false,
                'message' => 'Tidak bisa hapus pengunjung, status masih PEMINJAMAN'
            ]);
        }

        // Hapus file jika ada
        if ($pengunjung->foto_buku && Storage::disk('public')->exists($pengunjung->foto_buku)) {
            Storage::disk('public')->delete($pengunjung->foto_buku);
        }
        if ($pengunjung->img_barcode && Storage::disk('public')->exists($pengunjung->img_barcode)) {
            Storage::disk('public')->delete($pengunjung->img_barcode);
        }

        $pengunjung->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }


    public function pengunjungPeminjam()
    {
        // Ambil semua data peminjaman beserta relasi buku
        $peminjaman = PengunjungPerpustakaan::with('buku')
            ->orderBy('created_at', 'desc')->where('status', 'PEMINJAMAN')
            ->get();

        return view('admin-banceuy.peminjaman', compact('peminjaman'));
    }

    public function pengunjungPengembalian()
    {
        // Ambil semua data peminjaman beserta relasi buku
        $peminjaman = PengunjungPerpustakaan::with('buku')
            ->orderBy('created_at', 'desc')->where('status', 'PENGEMBALIAN')
            ->get();

        return view('admin-banceuy.pengembalian', compact('peminjaman'));
    }




    public function laporanPerpustakaan()
    {
        // ================= Statistik Ringkas =================
        $total_buku = Buku::count();
        $total_pengunjung = PengunjungPerpustakaan::count();
        $total_peminjaman = PengunjungPerpustakaan::where('status', 'PEMINJAMAN')->count();
        $total_pengembalian = PengunjungPerpustakaan::where('status', 'PENGEMBALIAN')->count();
        $total_barcode_valid = PengunjungPerpustakaan::where('status_barcode', 'VALID')->count();
        $total_barcode_expired = PengunjungPerpustakaan::where('status_barcode', 'EXPIRED')->count();
        $total_kategori = KategoriBuku::count();

        // ================= Grafik Peminjaman =================
        $grafik_harian = PengunjungPerpustakaan::select(
            DB::raw('DATE(created_at) as tanggal'),
            DB::raw('count(*) as total')
        )->groupBy('tanggal')->orderBy('tanggal')->get();

        $grafik_bulanan = PengunjungPerpustakaan::select(
            DB::raw('DATE_FORMAT(created_at, "%Y-%m") as bulan'),
            DB::raw('count(*) as total')
        )->groupBy('bulan')->orderBy('bulan')->get();

        $grafik_tahunan = PengunjungPerpustakaan::select(
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('count(*) as total')
        )->groupBy('tahun')->orderBy('tahun')->get();

        // ================= Return View =================
        return view('admin-banceuy.laporan-perpustakaan', [
            'total_buku' => $total_buku,
            'total_pengunjung' => $total_pengunjung,
            'total_peminjaman' => $total_peminjaman,
            'total_pengembalian' => $total_pengembalian,
            'total_barcode_valid' => $total_barcode_valid,
            'total_barcode_expired' => $total_barcode_expired,
            'total_kategori' => $total_kategori,
            'grafik_harian' => $grafik_harian,
            'grafik_bulanan' => $grafik_bulanan,
            'grafik_tahunan' => $grafik_tahunan,
        ]);
    }
}
