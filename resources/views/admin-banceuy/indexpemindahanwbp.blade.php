@extends('admin-banceuy.partisi.main')

@section('content')

<div class="container-fluid my-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <a 
            href="{{ route('sistemlaporan') }}"
            class="btn btn-secondary">
            
            <i class="bi me-1"></i>
            Kembali
            
        </a>

        <a href="{{ route('pemindahanwbp.create') }}"
           class="btn btn-primary">
            Tambah Kegiatan
        </a>

    </div>
    
    <h4 class="fw-bold mb-4">
        Data Laporan Pemindahan WBP
    </h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Tujuan</th>
                            <th>Status</th>
                            <th width="220">
                                Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($kegiatan as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->hari_tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $item->lokasi }}</td>
                            <td>{{ $item->lokasi_tujuan }}</td>
                            <td>
                                @if(strtolower($item->status)=='selesai')
                                    <span class="badge bg-success">Selesai</span>
                                @else
                                    <span class="badge bg-warning text-dark">Draft</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">

                                    @if(strtolower($item->status)=='draft')
                                        <a href="{{ route('pemindahanwbp.edit',$item->id) }}"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <a href="{{ route('pemindahanwbp.destroy',$item->id) }}"
                                           onclick="return confirm('Yakin hapus?')"
                                           class="btn btn-danger btn-sm">
                                            Delete
                                        </a>
                                    @else
                                        <button class="btn btn-secondary btn-sm" disabled>
                                            Locked
                                        </button>

                                        <a href="{{ route('pemindahanwbp.download',$item->id) }}"
                                           class="btn btn-primary btn-sm">
                                            DOCX
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Belum ada data
                            </td>
                        </tr>
                    @endforelse
                    </tbody>

                </table>
            </div>

            {{ $kegiatan->links() }}

        </div>
    </div>
</div>

@endsection