@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        .input-error {
            color: #dc3545;
            font-size: 13px;
            margin-top: 4px;
        }

        .input-invalid {
            border-color: #dc3545;
        }
    </style>

    <div class="container-fluid my-3">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <div>
                <h4 class="fw-bold mb-1">Kategori</h4>
                <small class="text-muted">Kelola kategori buku</small>
            </div>

        </div>


        <form method="GET" class="mb-3">

            <div class="input-group">

                <input type="text" name="search" class="form-control" placeholder="Cari kategori..."
                    value="{{ request('search') }}">

                <button class="btn btn-primary">
                    Cari
                </button>

            </div>

        </form>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



        {{-- FORM TAMBAH --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">

            <div class="card-body">

                <h5 class="fw-bold mb-3">Tambah Kategori</h5>

                <form id="formKategori" method="POST" action="{{ route('admin.kategori.store') }}">

                    @csrf

                    <div class="row">

                        <div class="col-md-6">

                            <label class="form-label">Nama Kategori</label>

                            <input type="text" name="nama" id="nama" class="form-control text-uppercase">

                            <div id="error_nama" class="input-error"></div>

                        </div>

                        <div class="col-md-6 d-flex align-items-end">

                            <button class="btn btn-success">
                                Tambah
                            </button>

                        </div>

                    </div>

                </form>

            </div>

        </div>



        {{-- TABLE --}}
        <div class="card border-0 shadow-sm rounded-4">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                            <tr>

                                <th width="60">No</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th width="120">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($kategoris as $i => $kategori)
                                <tr>

                                    <td>{{ $kategoris->firstItem() + $i }}</td>

                                    <td>{{ $kategori->nama }}</td>

                                    <td>{{ $kategori->slug }}</td>

                                    <td>

                                        <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus kategori?')">

                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>

                                        </form>

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <div class="mt-3">

            {{ $kategoris->links() }}

        </div>

    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById("formKategori")
            const nama = document.getElementById("nama")



            /* FUNCTION ERROR */

            function setError(input, message) {

                input.classList.add("input-invalid")
                document.getElementById("error_" + input.id).innerText = message

            }

            function clearError(input) {

                input.classList.remove("input-invalid")
                document.getElementById("error_" + input.id).innerText = ""

            }



            /* AUTO UPPERCASE + FILTER */

            nama.addEventListener("input", function() {

                this.value = this.value
                    .replace(/[^a-zA-Z\s]/g, '')
                    .toUpperCase()

            })



            /* VALIDASI */

            function validNama() {

                let value = nama.value.trim()

                if (value === "") {
                    setError(nama, "Nama kategori wajib diisi")
                    return false
                }

                if (value.length < 3) {
                    setError(nama, "Minimal 3 karakter")
                    return false
                }

                if (!/^[A-Z\s]+$/.test(value)) {
                    setError(nama, "Hanya huruf dan spasi")
                    return false
                }

                clearError(nama)
                return true

            }



            /* REALTIME VALIDATION */

            nama.addEventListener("keyup", validNama)



            /* SUBMIT */

            form.addEventListener("submit", function(e) {

                let valid = true

                if (!validNama()) valid = false

                if (!valid) {

                    e.preventDefault()

                }

            })

        })
    </script>
@endsection
