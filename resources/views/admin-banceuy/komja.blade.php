@extends('admin-banceuy.partisi.main')

@section('content')

<style>

/* ================= CARD (SAMA PERSIS WBP) ================= */
.main-row {
    display: block;
    background: #f8f9fa;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    transition: .2s;
}

.main-row:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

.nama-col {
    font-size: 16px;
    font-weight: 700;
    color: #2c3e50;
}

.sub-info {
    font-size: 13px;
    color: #6c757d;
    margin-top: 4px;
}

.wbp-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 14px;
}

/* ================= MODAL TAMBAH ================= */
#modalTambahPetugas{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    z-index:10000;
    overflow-y:auto;
}

.tambah-card{
    background:#fff;
    width:95%;
    max-width:700px;
    margin:40px auto;
    border-radius:18px;
    padding:24px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
}

.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
}

.form-group{
    display:flex;
    flex-direction:column;
}

.form-group.full{
    grid-column:1 / -1;
}

.form-group label{
    font-size:13px;
    font-weight:600;
    margin-bottom:6px;
    color:#374151;
}

.form-group input,
.form-group select,
.form-group textarea{
    border:1px solid #dcdfe4;
    border-radius:10px;
    padding:10px 12px;
    outline:none;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus{
    border-color:#2563eb;
}

.modal-footer-custom{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    margin-top:20px;
}

.error-text{
    display:block;
    min-height:18px;
    margin-top:5px;
    color:#dc3545;
    font-size:12px;
    font-weight:500;
}

.form-group input,
.form-group select,
.form-group textarea{
    width:100%;
    padding:10px 12px;
    border:1px solid #d1d5db;
    border-radius:10px;
    transition:.2s;
}

.input-error{
    border-color:#dc3545 !important;
}

.input-success{
    border-color:#198754 !important;
}

@media(max-width:768px){

    .form-grid{
        grid-template-columns:1fr;
    }

}

@media(max-width:768px){
    .wbp-grid { grid-template-columns:1fr; }
}

/* SEARCH */
.search-box {
    border-radius: 12px;
    height: 48px;
    border: 1px solid #dee2e6;
}

/* BUTTON */
.action-btn{
    border:none;
    padding:6px 12px;
    font-size:12px;
    border-radius:8px;
    font-weight:600;
}

.btn-detail{
    background:#111827;
    color:#fff;
}

.btn-delete{
    background:#ef4444;
    color:#fff;
}

.btn-detail:hover{background:#1f2937;}
.btn-delete:hover{background:#dc2626;}

/* MODAL */
#modalDetail {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    z-index:9999;
}

.detail-card{
    background:#fff;
    max-width:500px;
    margin:7% auto;
    padding:22px;
    border-radius:18px;
}

</style>

<div class="container-fluid my-3">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">

        <div>
            <h4 class="fw-bold mb-1">
                Data Komandan Jaga
                <span class="badge bg-primary ms-2">
                    {{ $countAktif ?? 0 }}
                </span>
            </h4>
            <small class="text-secondary">Kelola petugas jaga</small>
        </div>
    
        <button
            type="button"
            id="btnTambahPetugas"
            class="btn btn-primary rounded-3 px-4">
        
            + Tambah Petugas
        </button>
    
    </div>

    {{-- SEARCH (SAMA PERSIS WBP) --}}
    <input type="text"
        id="search"
        class="form-control search-box mb-3"
        placeholder="Cari nama / NIP...">

    {{-- LIST --}}
    <div id="tableWrapper">

        <div class="card shadow-sm border-0">
            <div class="card-body">

                <div class="wbp-grid">

                    @forelse($komandanJaga as $k)

                        <div class="main-row d-flex gap-3">

                            {{-- FOTO --}}
                            <div style="flex:0 0 90px;">

                                @if(!empty($k->foto))
                                    <img src="{{ asset($k->foto) }}"
                                        style="width:90px;height:90px;object-fit:cover;border-radius:12px;">
                                @else
                                    <div style="width:90px;height:90px;background:#e9ecef;border-radius:12px;"></div>
                                @endif

                            </div>

                            {{-- INFO --}}
                            <div style="flex:1;">

                                <div class="nama-col">
                                    {{ $k->nama_petugas ?? '-' }}
                                </div>

                                <div class="sub-info">
                                    NIP: {{ $k->nip ?? '-' }}
                                </div>

                                <div class="sub-info">
                                    {{ $k->jabatan ?? '-' }} • {{ $k->regu_jaga ?? '-' }}
                                </div>

                                <span class="badge bg-secondary mt-2">
                                    {{ $k->status ?? '-' }}
                                </span>

                                <div class="mt-2 d-flex gap-2">

                                    <button class="action-btn btn-detail"
                                        data-id="{{ $k->id }}">
                                        Detail
                                    </button>

                                    <button class="action-btn btn-delete"
                                        data-id="{{ $k->id }}">
                                        Hapus
                                    </button>

                                </div>

                            </div>

                        </div>

                    @empty
                        <div class="text-center py-5 text-secondary">
                            Data kosong
                        </div>
                    @endforelse

                </div>

            </div>
        </div>

    </div>

</div>

{{-- ================= MODAL DETAIL ================= --}}
<div id="modalDetail">

    <div class="detail-card">

        <div class="d-flex justify-content-between mb-3">
            <h5 class="fw-bold">Detail Komandan</h5>
            <button class="btn btn-danger btn-sm" id="closeModal">✕</button>
        </div>

        <div id="detailBody">Loading...</div>

    </div>

</div>


{{-- ================= MODAL TAMBAH PETUGAS ================= --}}
<div id="modalTambahPetugas">

    <div class="tambah-card">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0">Tambah Petugas</h4>

            <button
                type="button"
                class="btn btn-danger btn-sm"
                id="closeTambahPetugas">
                ✕
            </button>
        </div>

        <form
            id="formTambahPetugas"
            action="{{ url('/admin-banceuy/komja/store') }}"
            method="POST"
            enctype="multipart/form-data"
            autocomplete="off">

            @csrf

            <div class="form-grid">

                <!-- Nama -->
                <div class="form-group">
                    <label>Nama Petugas <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        id="nama_petugas"
                        name="nama_petugas"
                        maxlength="100"
                        style="text-transform: uppercase;"
                        required>

                    <small class="error-text" id="err_nama_petugas"></small>
                </div>

                <!-- NIP -->
                <div class="form-group">
                    <label>NIP <span class="text-danger">*</span></label>
                    <input
                        type="text"
                        id="nip"
                        name="nip"
                        maxlength="18"
                        style="text-transform: uppercase;"
                        required>

                    <small class="error-text" id="err_nip"></small>
                </div>

                <!-- Pangkat -->
                <div class="form-group">
                    <label>Pangkat</label>
                    <input
                        type="text"
                        id="pangkat"
                        name="pangkat"
                        maxlength="50"
                        style="text-transform: uppercase;">

                    <small class="error-text" id="err_pangkat"></small>
                </div>

                <!-- Golongan -->
                <div class="form-group">
                    <label>Golongan</label>
                    <input
                        type="text"
                        id="golongan"
                        name="golongan"
                        maxlength="30"
                        style="text-transform: uppercase;">

                    <small class="error-text" id="err_golongan"></small>
                </div>

                <!-- Jabatan -->
                <div class="form-group">
                    <label>Jabatan</label>
                    <input
                        type="text"
                        id="jabatan"
                        name="jabatan"
                        maxlength="100"
                        style="text-transform: uppercase;">

                    <small class="error-text" id="err_jabatan"></small>
                </div>

                <!-- Regu -->
                <div class="form-group">
                    <label>Regu Jaga</label>

                    <select
                        id="regu_jaga"
                        name="regu_jaga"
                        style="text-transform: uppercase;">

                        <option value="">-- Pilih Regu --</option>
                        <option value="Regu A">Regu A</option>
                        <option value="Regu B">Regu B</option>
                        <option value="Regu C">Regu C</option>
                        <option value="Regu D">Regu D</option>

                    </select>

                    <small class="error-text" id="err_regu_jaga"></small>
                </div>

                <!-- HP -->
                <div class="form-group">
                    <label>Nomor HP</label>

                    <input
                        type="text"
                        id="nomor_hp"
                        name="nomor_hp"
                        maxlength="15"
                        style="text-transform: uppercase;">

                    <small class="error-text" id="err_nomor_hp"></small>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label>Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        maxlength="100"
                        style="text-transform: uppercase;">

                    <small class="error-text" id="err_email"></small>
                </div>

                <!-- Status -->
                <div class="form-group">
                    <label>Status</label>

                    <select
                        id="status"
                        name="status"
                        style="text-transform: uppercase;">

                        <option value="Aktif">Aktif</option>
                        <option value="Nonaktif">Nonaktif</option>

                    </select>

                    <small class="error-text" id="err_status"></small>
                </div>

                <!-- Foto -->
                <div class="form-group">
                    <label>Foto</label>

                    <input
                        type="file"
                        id="foto"
                        name="foto"
                        accept=".jpg,.jpeg,.png"
                        style="text-transform: uppercase;">

                    <small class="error-text" id="err_foto"></small>
                </div>

                <!-- Keterangan -->
                <div class="form-group full">
                    <label>Keterangan</label>

                    <textarea
                        id="keterangan"
                        name="keterangan"
                        rows="4"
                        maxlength="500"
                        style="text-transform: uppercase;"></textarea>

                    <small class="error-text" id="err_keterangan"></small>
                </div>

            </div>

            <div class="modal-footer-custom">

                <button
                    type="button"
                    class="btn btn-secondary"
                    id="batalTambahPetugas">
                    Batal
                </button>

                <button
                    type="submit"
                    class="btn btn-primary"
                    id="btnSimpanPetugas">
                    Simpan
                </button>

            </div>

        </form>

    </div>

</div>

{{-- ================= SCRIPT (PAKE PATTERN WBP) ================= --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>

let currentStatus = 'Aktif';
let currentPage = 1;

function loadStatus(status,page=1){

    $('#tableWrapper').html(`<div class="text-center p-4">Loading...</div>`);

    $.get('/admin-banceuy/komja/filter',{
        status:status,
        page:page,
        search:$('#search').val()
    },function(res){

        let html = `<div class="card border-0 shadow-sm"><div class="card-body"><div class="wbp-grid">`;

        $.each(res.data,function(i,k){

            html += `
                <div class="main-row d-flex gap-3">

                    <div style="flex:0 0 90px;">
                        ${k.foto ? `<img src="/${k.foto}" style="width:90px;height:90px;object-fit:cover;border-radius:12px;">`
                        : `<div style="width:90px;height:90px;background:#e9ecef;border-radius:12px;"></div>`}
                    </div>

                    <div style="flex:1;">

                        <div class="nama-col">${k.nama_petugas ?? '-'}</div>
                        <div class="sub-info">NIP: ${k.nip ?? '-'}</div>
                        <div class="sub-info">${k.jabatan ?? '-'} • ${k.regu_jaga ?? '-'}</div>

                        <span class="badge bg-secondary mt-2">${k.status ?? '-'}</span>

                        <div class="mt-2 d-flex gap-2">
                            <button class="action-btn btn-detail" data-id="${k.id}">Detail</button>
                        </div>

                    </div>

                </div>
            `;
        });

        html += `</div></div></div>`;

        $('#tableWrapper').html(html);

    });

}

// SEARCH LIVE (SAMA WBP)
let t;
$('#search').on('input',function(){
    clearTimeout(t);
    t = setTimeout(()=>loadStatus(currentStatus,1),300);
});

// TAB optional (kalau mau nanti expand status)
$(document).on('click','.status-tab',function(){
    $('.status-tab').removeClass('active');
    $(this).addClass('active');
    currentStatus = $(this).data('status');
    loadStatus(currentStatus,1);
});

// DETAIL MODAL
$(document).on('click','.btn-detail',function(){

    let id = $(this).data('id');

    $('#modalDetail').fadeIn();
    $('#detailBody').html('Loading...');

    $.get('/admin-banceuy/komja/'+id+'/detail',function(k){

        $('#detailBody').html(`
            <p><b>Nama:</b> ${k.nama_petugas}</p>
            <p><b>NIP:</b> ${k.nip}</p>
            <p><b>Pangkat:</b> ${k.pangkat ?? '-'}</p>
            <p><b>Golongan:</b> ${k.golongan ?? '-'}</p>
            <p><b>Jabatan:</b> ${k.jabatan ?? '-'}</p>
            <p><b>Regu:</b> ${k.regu_jaga ?? '-'}</p>
            <p><b>No HP:</b> ${k.nomor_hp ?? '-'}</p>
            <p><b>Email:</b> ${k.email ?? '-'}</p>
            <p><b>Status:</b> ${k.status ?? '-'}</p>
            <p><b>Lokasi:</b> ${k.lokasi_penugasan ?? '-'}</p>
            <p><b>Keterangan:</b> ${k.keterangan ?? '-'}</p>
        `);

    });

});

// ================= MODAL TAMBAH PETUGAS =================

$('#btnTambahPetugas').on('click', function () {
    $('#modalTambahPetugas').fadeIn(200);
});

$('#closeTambahPetugas, #batalTambahPetugas').on('click', function () {
    $('#modalTambahPetugas').fadeOut(200);
});

$('#modalTambahPetugas').on('click', function (e) {
    if (e.target === this) {
        $(this).fadeOut(200);
    }
});

$('#closeModal').click(()=>$('#modalDetail').fadeOut());

</script>



<!-- ================= VALIDASI REALTIME ================= -->
<script>


function setError(id, pesan){
    $('#' + id)
        .addClass('input-error')
        .removeClass('input-success');

    $('#err_' + id).text(pesan);
}

function setSuccess(id){
    $('#' + id)
        .removeClass('input-error')
        .addClass('input-success');

    $('#err_' + id).text('');
}

// ================= NAMA =================

$('#nama_petugas').on('input', function(){

    let value = $(this).val();

    value = value.replace(/[^A-Za-z\s'.]/g,'');

    $(this).val(value);

    if(value.length < 3){

        setError('nama_petugas','Nama minimal 3 karakter.');

    }else{

        setSuccess('nama_petugas');

    }

});

// ================= NIP =================

$('#nip').on('input', function(){

    let value = $(this).val();

    value = value.replace(/\D/g,'');

    $(this).val(value);

    if(value.length != 18){

        setError('nip','NIP harus terdiri dari 18 digit.');

    }else{

        setSuccess('nip');

    }

});

// ================= PANGKAT =================

$('#pangkat').on('input', function(){

    if($(this).val().trim().length < 2){

        setError('pangkat','Pangkat terlalu pendek.');

    }else{

        setSuccess('pangkat');

    }

});

// ================= GOLONGAN =================

$('#golongan').on('input', function(){

    if($(this).val().trim().length < 1){

        setError('golongan','Golongan wajib diisi.');

    }else{

        setSuccess('golongan');

    }

});

// ================= JABATAN =================

$('#jabatan').on('input', function(){

    if($(this).val().trim().length < 3){

        setError('jabatan','Jabatan minimal 3 karakter.');

    }else{

        setSuccess('jabatan');

    }

});

// ================= REGU =================

$('#regu_jaga').on('change', function(){

    if($(this).val()==''){

        setError('regu_jaga','Pilih regu.');

    }else{

        setSuccess('regu_jaga');

    }

});

// ================= NOMOR HP =================

$('#nomor_hp').on('input', function(){

    let value = $(this).val();

    value = value.replace(/\D/g,'');

    $(this).val(value);

    if(value.length < 10){

        setError('nomor_hp','Nomor HP minimal 10 digit.');

    }else{

        setSuccess('nomor_hp');

    }

});

// ================= EMAIL =================

$('#email').on('input', function(){

    let email = $(this).val();

    let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!regex.test(email)){

        setError('email','Format email tidak valid.');

    }else{

        setSuccess('email');

    }

});

// ================= FOTO =================

$('#foto').on('change', function(){

    const file = this.files[0];

    if(!file){

        return;

    }

    const ext = file.name.split('.').pop().toLowerCase();

    if(!['jpg','jpeg','png'].includes(ext)){

        setError('foto','Foto hanya JPG, JPEG atau PNG.');

        $(this).val('');

        return;

    }

    if(file.size > 2 * 1024 * 1024){

        setError('foto','Ukuran maksimal 2 MB.');

        $(this).val('');

        return;

    }

    setSuccess('foto');

});

// ================= KETERANGAN =================

$('#keterangan').on('input', function(){

    if($(this).val().length > 500){

        setError('keterangan','Maksimal 500 karakter.');

    }else{

        setSuccess('keterangan');

    }

});

// ================= SUBMIT =================

$('#formTambahPetugas').on('submit', function(e){

    $('#nama_petugas,#nip,#pangkat,#golongan,#jabatan,#regu_jaga,#nomor_hp,#email,#foto')
        .trigger('input');

    $('#regu_jaga').trigger('change');
    $('#foto').trigger('change');

    if($('.input-error').length){

        e.preventDefault();

    }

});

</script>

@endsection