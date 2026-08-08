@extends('admin-banceuy.partisi.main')

@section('content')

<style>
.hp-grid{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:15px;
}

.hp-card{
    background:#fff;
    border-radius:14px;
    padding:16px;
    box-shadow:0 2px 10px rgba(0,0,0,.08);
    display:flex;
    gap:15px;
    align-items:center;
}

.barcode-box{
    flex:0 0 120px;
}

.barcode-box img{
    width:100%;
    border-radius:8px;
    border:1px solid #ddd;
}

.info-box{
    flex:1;
}

.nama{
    font-size:18px;
    font-weight:700;
    margin-bottom:4px;
}

.sub{
    font-size:13px;
    color:#666;
    margin-bottom:2px;
}

.modal-custom{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    z-index:99999;
    overflow:auto;
    padding:20px;
}

.modal-content-custom{
    background:#fff;
    max-width:900px;
    margin:20px auto;
    border-radius:14px;
    padding:20px;
}

.hp-item{
    background:#f8f9fa;
    border-radius:10px;
    padding:15px;
    margin-bottom:15px;
    border:1px solid #ddd;
}

@media(max-width:768px){

    .hp-grid{
        grid-template-columns:1fr;
    }

    .hp-card{
        flex-direction:column;
        text-align:center;
    }
}
</style>

<div class="container-fluid my-3">

    <div class="d-flex justify-content-between align-items-center mb-3">

        <h4 class="fw-bold">
            Data HP Petugas Banceuy
        </h4>

        <button class="btn btn-primary" id="btnTambah">
            Tambah Data
        </button>

    </div>

    <div class="hp-grid">

        @forelse($data as $row)

            <div class="hp-card">

                <div class="barcode-box">

                    @if($row->img_barcode)

                        <img src="{{ asset('storage/'.$row->img_barcode) }}">

                    @else

                        <div class="text-muted">
                            Barcode kosong
                        </div>

                    @endif

                </div>

                <div class="info-box">

                    <div class="nama">
                        {{ $row->nama ?? '-' }}
                    </div>

                    <div class="sub">
                        NIP : {{ $row->nip ?? '-' }}
                    </div>

                    <div class="sub">
                        Jabatan : {{ $row->jabatan ?? '-' }}
                    </div>

                    <div class="sub">
                        UPT : {{ $row->nama_upt ?? '-' }}
                    </div>

                    <hr>

                    @foreach(($row->jenis_hp ?? []) as $hp)

                        <span class="badge bg-dark">
                            {{ $hp }}
                        </span>
                    
                    @endforeach

                    <div class="mt-3 d-flex gap-2 flex-wrap">

                        <button
                            class="btn btn-warning btn-sm btnEdit"
                            data-id="{{ $row->id }}">

                            Edit
                        </button>

                        <button
                            class="btn btn-danger btn-sm btnDelete"
                            data-id="{{ $row->id }}">

                            Hapus
                        </button>

                    </div>

                </div>

            </div>

        @empty

            <div class="text-center text-muted py-5">
                Data kosong
            </div>

        @endforelse

    </div>

</div>

{{-- ================= MODAL ================= --}}
<div id="modalHp" class="modal-custom">

    <div class="modal-content-custom">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h5 id="modalTitle">
                Form Data HP
            </h5>

            <button class="btn btn-danger btn-sm" id="closeModal">
                X
            </button>

        </div>

        <form id="formHp" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" id="id">

            {{-- ================= HEADER ================= --}}
            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>Logo</label>

                    <input type="file"
                           name="logo"
                           class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Title</label>

                    <input type="text"
                           name="title"
                           id="title"
                           class="form-control">

                </div>

                <div class="col-md-12 mb-3">

                    <label>Subtitle</label>

                    <input type="text"
                           name="subtitle"
                           id="subtitle"
                           class="form-control">

                </div>

            </div>

            <hr>

            {{-- ================= PETUGAS ================= --}}
            <div class="row">

                <div class="col-md-6 mb-3">

                    <label>Nama UPT</label>

                    <input type="text"
                           name="nama_upt"
                           id="nama_upt"
                           class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Foto Petugas</label>

                    <input type="file"
                           name="foto_petugas"
                           class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>Nama</label>

                    <input type="text"
                           name="nama"
                           id="nama"
                           class="form-control">

                </div>

                <div class="col-md-6 mb-3">

                    <label>NIP</label>

                    <input type="text"
                           name="nip"
                           id="nip"
                           class="form-control">

                </div>

                <div class="col-md-12 mb-3">

                    <label>Jabatan</label>

                    <input type="text"
                           name="jabatan"
                           id="jabatan"
                           class="form-control">

                </div>

            </div>

            <hr>

            {{-- ================= HP 1 ================= --}}
            <div class="hp-item">

                <h6 class="fw-bold mb-3">
                    Handphone 1
                </h6>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Foto HP 1</label>

                        <input type="file"
                               name="foto_handphone[]"
                               class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Jenis HP 1</label>

                        <input type="text"
                               name="jenis_hp[]"
                               id="jenis_hp_1"
                               class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Warna HP 1</label>

                        <input type="text"
                               name="warna_hp[]"
                               id="warna_hp_1"
                               class="form-control">

                    </div>

                </div>

            </div>

            {{-- ================= HP 2 ================= --}}
            <div class="hp-item">

                <h6 class="fw-bold mb-3">
                    Handphone 2
                </h6>

                <div class="row">

                    <div class="col-md-4 mb-3">

                        <label>Foto HP 2</label>

                        <input type="file"
                               name="foto_handphone[]"
                               class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Jenis HP 2</label>

                        <input type="text"
                               name="jenis_hp[]"
                               id="jenis_hp_2"
                               class="form-control">

                    </div>

                    <div class="col-md-4 mb-3">

                        <label>Warna HP 2</label>

                        <input type="text"
                               name="warna_hp[]"
                               id="warna_hp_2"
                               class="form-control">

                    </div>

                </div>

            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">

                <button type="button"
                        class="btn btn-secondary"
                        id="closeModal2">

                    Tutup
                </button>

                <button type="submit"
                        class="btn btn-primary">

                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

$(function(){

// ================= OPEN MODAL =================
$('#btnTambah').click(function(){

    $('#formHp')[0].reset();

    $('#id').val('');

    $('#modalTitle').text('Tambah Data HP');

    $('#modalHp').fadeIn(200);
});

// ================= CLOSE =================
$('#closeModal, #closeModal2').click(function(){

    $('#modalHp').fadeOut(200);
});

// ================= EDIT =================
$(document).on('click','.btnEdit',function(){

    let id = $(this).data('id');

    $.get('/admin-banceuy/hp-gasban/show/' + id,function(res){

        $('#id').val(res.id);

        $('#title').val(res.title);
        $('#subtitle').val(res.subtitle);

        $('#nama').val(res.nama);
        $('#nip').val(res.nip);
        $('#jabatan').val(res.jabatan);

        $('#nama_upt').val(res.nama_upt);

        let jenis = res.jenis_hp ?? [];
        let warna = res.warna_hp ?? [];

        $('#jenis_hp_1').val(jenis[0] ?? '');
        $('#jenis_hp_2').val(jenis[1] ?? '');

        $('#warna_hp_1').val(warna[0] ?? '');
        $('#warna_hp_2').val(warna[1] ?? '');

        $('#modalTitle').text('Edit Data HP');

        $('#modalHp').fadeIn(200);

    });

});

// ================= STORE =================
$('#formHp').submit(function(e){

    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({

        url:'/admin-banceuy/hp-gasban/store',
        type:'POST',
        data:formData,

        processData:false,
        contentType:false,

        success:function(res){

            alert(res.message);

            $('#modalHp').fadeOut(200);

            location.reload();
        },

        error:function(xhr){

            if(xhr.status === 422){

                let errors = xhr.responseJSON.errors;

                let txt = '';

                $.each(errors,function(i,v){

                    txt += v[0] + '\n';

                });

                alert(txt);

            }else{

                alert('Terjadi kesalahan');
            }
        }
    });

});

// ================= DELETE =================
$(document).on('click','.btnDelete',function(){

    if(!confirm('Hapus data ini ?')){
        return;
    }

    let id = $(this).data('id');

    $.ajax({

        url:'/admin-banceuy/hp-gasban/delete/' + id,
        type:'DELETE',

        data:{
            _token:'{{ csrf_token() }}'
        },

        success:function(res){

            alert(res.message);

            location.reload();
        }

    });

});

});
</script>

@endsection