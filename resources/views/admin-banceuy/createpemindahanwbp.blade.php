@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

    <a href="{{ route('pemindahanwbp.index') }}"
       class="btn btn-secondary d-inline-block w-auto mb-3">
        Kembali
    </a>

    <div class="card">
        <div class="card-header">
            <strong>Tambah Kegiatan Pemindahan WBP</strong>
        </div>

        <div class="card-body">

            <form action="{{ route('pemindahanwbp.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Hari/Tanggal</label>
                        <input type="date" name="hari_tanggal" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Jam Tiba</label>
                        <input type="time" name="jam_tiba" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Lokasi</label>
                    <input type="text" name="lokasi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Lokasi Tujuan</label>
                    <input type="text" name="lokasi_tujuan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Izin</label>
                    <input type="text" name="nomor_surat_izin" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nomor Surat Persetujuan</label>
                    <input type="text" name="nomor_surat_persetujuan" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama WBP</label>
                    <input type="text" name="nama_wbp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jumlah WBP</label>
                    <input type="number" name="jumlah_wbp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jumlah Anggota Petugas Lapas</label>
                    <input type="number" name="jumlah_petugas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jumlah Anggota Polisi</label>
                    <input type="number" name="jumlah_polisi" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Total Update WBP</label>
                    <input type="number" name="total_update_wbp" class="form-control" required>
                </div>

                <button class="btn btn-primary w-auto">
                    Simpan
                </button>

            </form>
        </div>
    </div>
</div>

@endsection