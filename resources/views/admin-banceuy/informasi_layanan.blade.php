@extends('admin-banceuy.partisi.main')

@section('content')
<div class="container-fluid my-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">Informasi Layanan</h4>
            <small class="text-muted">Kelola informasi layanan + sosial media + contact support</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-semibold mb-1">Ada error:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ===================== FORM (ADD/EDIT) ===================== --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-0" id="formTitle">Tambah Informasi Layanan</h5>
                    <small class="text-muted" id="formSubtitle">Buat informasi layanan baru</small>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary rounded-pill px-4" id="btnTambah">
                        <i class="feather-plus me-1"></i> Tambah
                    </button>

                    <button type="button" class="btn btn-light rounded-pill px-4 d-none" id="btnBatal">
                        <i class="feather-x me-1"></i> Batal Edit
                    </button>
                </div>
            </div>

            <form id="formInfo" action="{{ url('/admin-banceuy/informasi-layanan') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="edit_id" value="">

                <div class="row g-3">

                    {{-- Judul --}}
                    <div class="col-lg-8">
                        <label class="form-label fw-semibold">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control"
                               placeholder="Contoh: Informasi Kunjungan / Titip Barang / Jam Layanan..." required>
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold">Aktifkan Informasi</label>
                        </div>
                    </div>

                    {{-- Thumbnail --}}
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Thumbnail</label>
                        <input type="file" name="thumbnail" id="thumbnail" class="form-control" accept="image/*">
                        <small class="text-muted">Max 2MB (JPG/PNG/WebP)</small>

                        <div class="mt-2 d-none" id="thumbPreviewWrap">
                            <small class="text-muted d-block mb-1">Thumbnail saat ini:</small>
                            <img id="thumbPreview" src="" class="rounded-3 border"
                                 style="width:160px; height:95px; object-fit:cover;">
                        </div>
                    </div>

                    {{-- Contact Support --}}
                    <div class="col-lg-6">
                        <label class="form-label fw-semibold">Contact Support</label>
                        <input type="text" name="contact_support" id="contact_support" class="form-control"
                               placeholder="Contoh: WhatsApp 08xxxx / Telepon / Email">
                        <small class="text-muted">Opsional</small>
                    </div>

                    {{-- Sosial Media --}}
                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Instagram</label>
                        <input type="text" name="instagram" id="instagram" class="form-control"
                               placeholder="Contoh: @lapasbanceuy">
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Facebook</label>
                        <input type="text" name="facebook" id="facebook" class="form-control"
                               placeholder="Contoh: Lapas Banceuy">
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Twitter</label>
                        <input type="text" name="twitter" id="twitter" class="form-control"
                               placeholder="Contoh: @lapasbanceuy">
                    </div>

                    {{-- Isi --}}
                    <div class="col-12">
                        <label class="form-label fw-semibold">Isi Informasi</label>

                        <input id="isi" type="hidden" name="isi" value="">
                        <trix-editor input="isi" class="trix-content"></trix-editor>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2" id="btnSubmit">
                        <i class="feather-save me-1"></i> Simpan Informasi
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ===================== TABLE LIST ===================== --}}
    <div class="card border-0 shadow-sm rounded-4 ">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px;">No</th>
                            <th style="width:110px;">Thumb</th>
                            <th>Judul</th>
                            <th style="width:170px;">Sosmed</th>
                            <th style="width:200px;">Contact</th>
                            <th style="width:160px;">Status</th>
                            <th style="width:220px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($data as $i => $item)
                            <tr>
                                <td class="text-muted">{{ $data->firstItem() + $i }}</td>

                                <td>
                                    @if($item->thumbnail)
                                        <img src="{{ asset('storage/'.$item->thumbnail) }}"
                                             class="rounded-3 border"
                                             style="width:90px; height:55px; object-fit:cover;">
                                    @else
                                        <div class="text-muted small">No Image</div>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-semibold">{{ $item->judul }}</div>
                                    <div class="text-muted small">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 70) }}
                                    </div>
                                </td>

                                <td class="text-muted small">
                                    <div><b>IG:</b> {{ $item->instagram ?: '-' }}</div>
                                    <div><b>FB:</b> {{ $item->facebook ?: '-' }}</div>
                                    <div><b>TW:</b> {{ $item->twitter ?: '-' }}</div>
                                </td>

                                <td class="text-muted small">
                                    {{ $item->contact_support ?: '-' }}
                                </td>

                                <td>
                                    @if($item->is_active)
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Nonaktif</span>
                                    @endif
                                </td>

                                <td class="text-end">
                                    <div class="d-flex flex-wrap justify-content-end gap-2">

                                        <button type="button"
                                            class="btn btn-warning btn-sm rounded-pill px-3 btnEdit d-flex align-items-center gap-1 w-100 w-sm-auto"
                                            data-id="{{ $item->id }}"
                                            data-judul="{{ e($item->judul) }}"
                                            data-isi="{{ e($item->isi) }}"
                                            data-active="{{ $item->is_active ? 1 : 0 }}"
                                            data-thumb="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : '' }}"
                                            data-instagram="{{ e($item->instagram) }}"
                                            data-facebook="{{ e($item->facebook) }}"
                                            data-twitter="{{ e($item->twitter) }}"
                                            data-contact_support="{{ e($item->contact_support) }}"
                                            >
                                            <i class="feather-edit"></i>
                                            <span>Edit</span>
                                        </button>

                                        <form action="{{ url('/admin-banceuy/informasi-layanan/'.$item->id.'/delete') }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto"
                                              onsubmit="return confirm('Yakin hapus data ini?')">
                                            @csrf
                                            <button type="submit"
                                                class="btn btn-danger btn-sm rounded-pill px-3 d-flex align-items-center gap-1 w-100">
                                                <i class="feather-trash"></i>
                                                <span>Hapus</span>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    Belum ada informasi layanan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        @if($data->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $data->links() }}
            </div>
        @endif
    </div>

</div>

{{-- TRIX --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.2/dist/trix.css">
<script src="https://cdn.jsdelivr.net/npm/trix@2.1.2/dist/trix.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formInfo");

    const formTitle = document.getElementById("formTitle");
    const formSubtitle = document.getElementById("formSubtitle");

    const btnTambah = document.getElementById("btnTambah");
    const btnBatal = document.getElementById("btnBatal");
    const btnSubmit = document.getElementById("btnSubmit");

    const inputJudul = document.getElementById("judul");
    const inputActive = document.getElementById("is_active");
    const inputIsiHidden = document.getElementById("isi");
    const inputThumb = document.getElementById("thumbnail");

    const inputInstagram = document.getElementById("instagram");
    const inputFacebook = document.getElementById("facebook");
    const inputTwitter = document.getElementById("twitter");
    const inputContact = document.getElementById("contact_support");

    const thumbPreviewWrap = document.getElementById("thumbPreviewWrap");
    const thumbPreview = document.getElementById("thumbPreview");

    function resetToCreateMode() {
        form.action = "{{ url('/admin-banceuy/informasi-layanan') }}";

        formTitle.innerText = "Tambah Informasi Layanan";
        formSubtitle.innerText = "Buat informasi layanan baru";

        btnSubmit.innerHTML = '<i class="feather-save me-1"></i> Simpan Informasi';
        btnBatal.classList.add("d-none");

        inputJudul.value = "";
        inputActive.checked = true;
        inputThumb.value = "";

        inputInstagram.value = "";
        inputFacebook.value = "";
        inputTwitter.value = "";
        inputContact.value = "";

        inputIsiHidden.value = "";
        document.querySelector("trix-editor").editor.loadHTML("");

        thumbPreviewWrap.classList.add("d-none");
        thumbPreview.src = "";
    }

    btnTambah.addEventListener("click", function () {
        resetToCreateMode();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    btnBatal.addEventListener("click", function () {
        resetToCreateMode();
    });

    document.querySelectorAll(".btnEdit").forEach(function (btn) {
        btn.addEventListener("click", function () {

            const id = this.getAttribute("data-id");
            const judul = this.getAttribute("data-judul");
            const isi = this.getAttribute("data-isi");
            const active = this.getAttribute("data-active");
            const thumb = this.getAttribute("data-thumb");

            const instagram = this.getAttribute("data-instagram");
            const facebook = this.getAttribute("data-facebook");
            const twitter = this.getAttribute("data-twitter");
            const contact_support = this.getAttribute("data-contact_support");

            form.action = "{{ url('/admin-banceuy/informasi-layanan') }}/" + id + "/update";

            formTitle.innerText = "Edit Informasi Layanan";
            formSubtitle.innerText = "Update informasi layanan yang dipilih";

            btnSubmit.innerHTML = '<i class="feather-refresh-cw me-1"></i> Update Informasi';
            btnBatal.classList.remove("d-none");

            inputJudul.value = judul || "";
            inputActive.checked = (active == "1");

            inputInstagram.value = instagram || "";
            inputFacebook.value = facebook || "";
            inputTwitter.value = twitter || "";
            inputContact.value = contact_support || "";

            if (thumb && thumb.length > 5) {
                thumbPreviewWrap.classList.remove("d-none");
                thumbPreview.src = thumb;
            } else {
                thumbPreviewWrap.classList.add("d-none");
                thumbPreview.src = "";
            }

            inputIsiHidden.value = isi || "";
            document.querySelector("trix-editor").editor.loadHTML(isi || "");

            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });

});
</script>
@endsection
