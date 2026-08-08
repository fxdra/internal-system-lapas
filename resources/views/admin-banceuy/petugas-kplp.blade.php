@extends('admin-banceuy.partisi.main')

@section('content')

<!-- JQUERY -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<style>

body{
    background: #f5f7fb;
}

/* =========================
   CARD
========================= */

.card-custom{
    border: none;
    border-radius: 24px;
    overflow: hidden;
    background: #fff;
}

.table td,
.table th{
    vertical-align: middle;
}

.badge-custom{
    background: #2563eb;
    color: white;
    padding: 8px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
}





/* =========================
   BUTTON
========================= */

.btn{
    border: none;
    border-radius: 12px;
    padding: 10px 18px;
    font-weight: 600;
    transition: .2s;
}

.btn-primary{
    background: #2563eb;
    color: white;
}

.btn-primary:hover{
    background: #1d4ed8;
}

.btn-warning{
    background: #f59e0b;
    color: white;
}

.btn-danger{
    background: #ef4444;
    color: white;
}

.btn-sm{
    padding: 8px 14px;
    font-size: 13px;
}

/* =========================
   CUSTOM MODAL
========================= */

.custom-modal{
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.6);
    backdrop-filter: blur(5px);

    display: flex;
    align-items: center;
    justify-content: center;

    opacity: 0;
    visibility: hidden;
    transition: .25s ease;

    z-index: 99999;
    padding: 20px;
}

.custom-modal.active{
    opacity: 1;
    visibility: visible;
}

.custom-modal-box{
    width: 100%;
    max-width: 520px;
    background: white;
    border-radius: 24px;
    overflow: hidden;

    transform: scale(.9) translateY(20px);
    transition: .25s ease;

    box-shadow:
        0 20px 40px rgba(0,0,0,.15);
}

.custom-modal.active .custom-modal-box{
    transform: scale(1) translateY(0);
}

.custom-modal-header{
    padding: 24px 28px 18px;
    border-bottom: 1px solid #f1f5f9;

    display: flex;
    justify-content: space-between;
    align-items: start;
}

.custom-modal-header h3{
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #0f172a;
}

.custom-modal-header small{
    color: #64748b;
}

.close-modal{
    width: 42px;
    height: 42px;
    border-radius: 12px;
    border: none;
    background: #f8fafc;
    cursor: pointer;
    font-size: 24px;
}

.close-modal:hover{
    background: #e2e8f0;
}

.custom-modal-body{
    padding: 24px 28px;
}

.form-group-custom{
    margin-bottom: 20px;
}

.form-group-custom label{
    display: block;
    margin-bottom: 10px;
    font-weight: 600;
    color: #0f172a;
}

.form-group-custom input{
    width: 100%;
    height: 52px;
    border-radius: 14px;
    border: 1px solid #dbe2ea;
    padding: 0 18px;
    outline: none;
    transition: .2s;
}

.form-group-custom input:focus{
    border-color: #2563eb;
    box-shadow:
        0 0 0 4px rgba(37,99,235,.12);
}

.form-group-custom small{
    display: block;
    margin-top: 8px;
    color: #64748b;
    font-size: 13px;
}

.custom-modal-footer{
    padding: 20px 28px 28px;

    display: flex;
    justify-content: end;
    gap: 12px;
}

.btn-cancel{
    border: none;
    background: #f1f5f9;
    color: #0f172a;
    padding: 12px 20px;
    border-radius: 14px;
    font-weight: 600;
}

.btn-save{
    border: none;
    background: #2563eb;
    color: white;
    padding: 12px 24px;
    border-radius: 14px;
    font-weight: 600;
}

.btn-save:hover{
    background: #1d4ed8;
}

@media(max-width:576px){

    .custom-modal-box{
        border-radius: 20px;
    }

    .custom-modal-header,
    .custom-modal-body,
    .custom-modal-footer{
        padding-left: 20px;
        padding-right: 20px;
    }

}

</style>

<div class="container py-4">

    <div class="card card-custom shadow-sm">

        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex align-items-center flex-wrap gap-3">

                <div>

                    <h3 class="fw-bold mb-1">
                        Data Petugas KPLP
                    </h3>

                    <small class="text-muted">
                        Management Data Petugas KPLP
                    </small>

                </div>

                <div class="ms-auto">

                    <button class="btn btn-primary"
                            id="btnTambah">

                        + Tambah Petugas

                    </button>

                </div>

            </div>

        </div>

        <div class="card-body">

            @if(session('success'))

                <div class="alert alert-success">

                    {{ session('success') }}

                </div>

            @endif

            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif

            <div class="table-responsive">

                <table class="table table-hover align-middle">

                    <thead class="table-light">

                        <tr>

                            <th width="80">No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Role</th>
                            <th width="220">Aksi</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($petugas as $item)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td class="fw-semibold">
                                {{ $item->nama }}
                            </td>

                            <td>
                                {{ $item->nip }}
                            </td>

                            <td>

                                <span class="badge-custom">

                                    {{ strtoupper($item->role) }}

                                </span>

                            </td>

                    
                                <td>
                                
                                    <div class="d-flex justify-content-between align-items-center">
                                
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-warning rounded-pill px-3 btnEdit"
                                
                                            data-id="{{ $item->id }}"
                                            data-nama="{{ $item->nama }}"
                                            data-nip="{{ $item->nip }}">
                                
                                            Edit
                                
                                        </button>
                                
                                        <form action="{{ route('petugas-kplp.destroy', $item->id) }}"
                                              method="POST"
                                              class="m-0">
                                
                                            @csrf
                                            @method('DELETE')
                                
                                            <button type="submit"
                                                    class="btn btn-sm btn-danger rounded-pill px-3"
                                                    onclick="return confirm('Yakin ingin menghapus data ini ?')">
                                
                                                Hapus
                                
                                            </button>
                                
                                        </form>
                                
                                    </div>
                                
                                </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="5" class="text-center py-5">

                                <img src="https://cdn-icons-png.flaticon.com/512/4076/4076549.png"
                                     width="90"
                                     class="mb-3">

                                <div class="text-muted">

                                    Data Petugas KPLP Belum Ada

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- CUSTOM MODAL -->

<div class="custom-modal"
     id="customModal">

    <div class="custom-modal-box">

        <div class="custom-modal-header">

            <div>

                <h3 id="modalTitle">
                    Tambah Petugas
                </h3>

                <small>
                    Form Data Petugas KPLP
                </small>

            </div>

            <button class="close-modal"
                    id="closeModal">

                &times;

            </button>

        </div>

        <form action="{{ route('petugas-kplp.store') }}"
              method="POST">

            @csrf

            <input type="hidden"
                   name="id"
                   id="id">

            <div class="custom-modal-body">

                <div class="form-group-custom">

                    <label>
                        Nama Lengkap
                    </label>

                    <input type="text"
                           name="nama"
                           id="nama"
                           placeholder="Masukkan nama petugas"
                           required>

                </div>

                <div class="form-group-custom">

                    <label>
                        NIP
                    </label>

                    <input type="text"
                           name="nip"
                           id="nip"
                           placeholder="Masukkan NIP"
                           required>

                </div>

                <div class="form-group-custom">

                    <label>
                        Password
                    </label>

                    <input type="password"
                           name="password"
                           id="password"
                           placeholder="Masukkan password">

                    <small>
                        Kosongkan jika tidak ingin mengganti password
                    </small>

                </div>

            </div>

            <div class="custom-modal-footer">

                <button type="button"
                        class="btn-cancel"
                        id="btnBatal">

                    Batal

                </button>

                <button type="submit"
                        class="btn-save">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>

<script>

$(document).ready(function(){

    function openModal(){

        $('#customModal').addClass('active');

        $('body').css('overflow', 'hidden');

    }

    function closeModal(){

        $('#customModal').removeClass('active');

        $('body').css('overflow', 'auto');

    }

    // TAMBAH

    $('#btnTambah').click(function(){

        $('#modalTitle').html('Tambah Petugas');

        $('#id').val('');
        $('#nama').val('');
        $('#nip').val('');
        $('#password').val('');

        openModal();

    });

    // EDIT

    $('.btnEdit').click(function(){

        let id   = $(this).data('id');
        let nama = $(this).data('nama');
        let nip  = $(this).data('nip');

        $('#modalTitle').html('Edit Petugas');

        $('#id').val(id);
        $('#nama').val(nama);
        $('#nip').val(nip);
        $('#password').val('');

        openModal();

    });

    // CLOSE BUTTON

    $('#closeModal, #btnBatal').click(function(){

        closeModal();

    });

    // CLICK OUTSIDE

    $('#customModal').click(function(e){

        if(e.target.id === 'customModal'){

            closeModal();

        }

    });

});

</script>

@endsection
