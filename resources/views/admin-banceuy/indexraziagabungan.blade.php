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

        <a
            href="{{ route('raziagabungan.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Tambah Kegiatan

        </a>

    </div>

    <h4 class="fw-bold mb-4 ms-1">
        Data Laporan Razia Gabungan
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

                <table class="table table-bordered table-hover align-middle">

                            <th width="60">
                                ID
                            </th>

                            <th>
                                Tanggal
                            </th>

                            <th>
                                Nomor Surat
                            </th>

                            <th>
                                Lokasi
                            </th>

                            <th>
                                Pimpinan
                            </th>

                            <th width="120">
                                Status
                            </th>

                            <th width="90">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($kegiatan as $item)

                            <tr>

                                <td>
                                    {{ $loop->iteration}} 
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($item->tanggal_kegiatan)->format('d-m-Y') }}
                                </td>

                                <td>
                                    {{ $item->nomor_surat_dirjen }}
                                </td>

                                <td>
                                    {{ $item->lokasi }}
                                </td>

                                <td>
                                    {{ $item->pimpinan_kegiatan }}
                                </td>

                                <td>

                                    @if(strtolower($item->status) == 'selesai')

                                        <span class="badge bg-success">
                                            Selesai
                                        </span>

                                    @else

                                        <span class="badge bg-warning text-dark">
                                            Draft
                                        </span>

                                    @endif

                                </td>

                                <td>

                                @if(strtolower($item->status) != 'selesai')
                            
                                    <div class="d-flex gap-2">
                            
                                        <a
                                            href="{{ route('raziagabungan.edit',$item->id) }}"
                                            class="btn btn-warning">
                            
                                            Edit
                            
                                        </a>
                            
                                        <form
                                            action="{{ route('raziagabungan.destroy',$item->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin hapus draft ini?')">
                            
                                            @csrf
                                            @method('DELETE')
                            
                                            <button
                                                type="submit"
                                                class="btn btn-danger">
                            
                                                Delete
                            
                                            </button>
                            
                                        </form>
                            
                                    </div>
                            
                                @else
                                
                                <div class="d-flex gap-2">
                                    <button
                                        class="btn btn-secondary"
                                        disabled>
                            
                                        Locked
                                        
                                    </button>
                                
                                <a
                                    href="{{ route('raziagabungan.download', $item->id) }}"
                                    class="btn btn-primary">
                        
                                    Download
                        
                                </a>
                                
                                @endif

                                </td>


                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-4">

                                    Belum ada data razia gabungan

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="mt-3">

                {{ $kegiatan->links() }}

            </div>

        </div>

    </div>

</div>

@endsection