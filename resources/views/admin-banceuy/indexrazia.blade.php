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
            href="{{ route('razia.create') }}"
            class="btn btn-primary">

            <i class="bi bi-plus-circle me-1"></i>
            Tambah Razia

        </a>

    </div>

    <h4 class="fw-bold mb- ms-1">
        Data Laporan Razia
    </h4>

</div>

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

            <thead>

                <tr style="background:#f8f9fa;">

        <th style="color:#000 !important;" width="60">
            ID
        </th>

        <th style="color:#000 !important;">
            Tanggal Razia
        </th>

        <th style="color:#000 !important;">
            Jam
        </th>

        <th style="color:#000 !important;">
            Lokasi
        </th>

        <th style="color:#000 !important;">
            Pimpinan Razia
        </th>

        <th style="color:#000 !important;" width="120">
            Status
        </th>

        <th style="color:#000 !important;" width="220">
            Aksi
        </th>

    </tr>

            </thead>


                <tbody>

                @forelse($kegiatan as $item)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ \Carbon\Carbon::parse($item->tanggal_razia)->format('d-m-Y') }}
                        </td>

                        <td>
                            {{ substr($item->jam_mulai,0,5) }}
                            -
                            {{ substr($item->jam_selesai,0,5) }}
                        </td>

                        <td>
                            {{ $item->lokasi }}
                        </td>

                        <td>
                            {{ $item->pimpinan_razia }}
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

    <div class="d-flex gap-1 flex-wrap">

        @if(strtolower($item->status) == 'draft')

            <a
                href="{{ route('razia.edit',$item->id) }}"
                class="btn btn-warning btn-sm">

                Edit

            </a>

            <form
                action="{{ route('razia.destroy',$item->id) }}"
                method="POST"
                onsubmit="return confirm('Yakin hapus draft ini?')">

                @csrf
                @method('DELETE')

                <button
                    type="submit"
                    class="btn btn-danger btn-sm">

                    Delete

                </button>

            </form>

        @elseif(strtolower($item->status) == 'selesai')

            <button
                class="btn btn-secondary btn-sm"
                disabled>

                Locked

            </button>

            <a
                href="{{ route('razia.download',$item->id) }}"
                class="btn btn-primary btn-sm">

                DOCX

            </a>

        @endif

    </div>
    </div>

</td>


                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-4">
                            Belum ada data razia

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
