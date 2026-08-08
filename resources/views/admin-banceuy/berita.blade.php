@extends('admin-banceuy.partisi.main')

@section('content')
<div class="container-fluid my-3">

    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
        <div>
            <h4 class="fw-bold mb-1">Berita</h4>
            <small class="text-muted">Kelola berita / artikel website</small>
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
                    <h5 class="fw-bold mb-0" id="formTitle">Tambah Berita</h5>
                    <small class="text-muted" id="formSubtitle">Buat berita baru</small>
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

            <form id="formBerita" action="{{ route('admin.berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" id="edit_id" value="">

                <div class="row g-3">
                    <div class="col-lg-8">
                        <label class="form-label fw-semibold">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control"
                               placeholder="Contoh: Kegiatan Pembinaan WBP..." required>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label fw-semibold">Tanggal Publish</label>
                        <input type="datetime-local" name="published_at" id="published_at" class="form-control">
                        <small class="text-muted">Kosongkan jika belum dipublish</small>
                    </div>

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

                    <div class="col-lg-6 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch"
                                   name="is_active" id="is_active" value="1" checked>
                            <label class="form-check-label fw-semibold">Aktifkan Berita</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Isi Berita</label>

                        <input id="isi" type="hidden" name="isi" value="">
                        <trix-editor input="isi" class="trix-content"></trix-editor>
                    </div>

                </div>

                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-pill px-4 py-2" id="btnSubmit">
                        <i class="feather-save me-1"></i> Simpan Berita
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- ===================== TABLE LIST ===================== --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:70px;">No</th>
                            <th style="width:110px;">Thumb</th>
                            <th>Judul</th>
                            <th style="width:160px;">Status</th>
                            <th style="width:180px;">Publish</th>
                            <th style="width:240px;" class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($beritas as $i => $item)
                            <tr>
                                <td class="text-muted">{{ $beritas->firstItem() + $i }}</td>

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
                                    <div class="text-muted small">/{{ $item->slug }}</div>
                                </td>

                                <td>
                                    @if($item->is_active)
                                        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">Nonaktif</span>
                                    @endif
                                </td>

                                <td class="text-muted small">
                                    {{ $item->published_at ? $item->published_at->format('d M Y H:i') : '-' }}
                                </td>

                                <td class="text-end">
                                    <div class="d-flex flex-wrap justify-content-end gap-2">
                                
                                        <button type="button"
                                            class="btn btn-warning btn-sm rounded-pill px-3 btnEdit d-flex align-items-center gap-1 w-100 w-sm-auto"
                                            data-id="{{ $item->id }}"
                                            data-judul="{{ e($item->judul) }}"
                                            data-isi="{{ e($item->isi) }}"
                                            data-active="{{ $item->is_active ? 1 : 0 }}"
                                            data-published="{{ $item->published_at ? $item->published_at->format('Y-m-d\TH:i') : '' }}"
                                            data-thumb="{{ $item->thumbnail ? asset('storage/'.$item->thumbnail) : '' }}">
                                            <i class="feather-edit"></i>
                                            <span>Edit</span>
                                        </button>
                                
                                        <form action="{{ route('admin.berita.destroy', $item->id) }}"
                                              method="POST"
                                              class="d-inline w-100 w-sm-auto"
                                              onsubmit="return confirm('Yakin hapus berita ini?')">
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
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada berita.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

        </div>

        @if($beritas->hasPages())
            <div class="card-footer bg-white border-0">
                {{ $beritas->links() }}
            </div>
        @endif
    </div>

</div>

{{-- TRIX --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/trix@2.1.2/dist/trix.css">
<script src="https://cdn.jsdelivr.net/npm/trix@2.1.2/dist/trix.umd.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("formBerita");

    const formTitle = document.getElementById("formTitle");
    const formSubtitle = document.getElementById("formSubtitle");

    const btnTambah = document.getElementById("btnTambah");
    const btnBatal = document.getElementById("btnBatal");
    const btnSubmit = document.getElementById("btnSubmit");

    const inputJudul = document.getElementById("judul");
    const inputPublished = document.getElementById("published_at");
    const inputActive = document.getElementById("is_active");
    const inputIsiHidden = document.getElementById("isi");
    const inputThumb = document.getElementById("thumbnail");

    const thumbPreviewWrap = document.getElementById("thumbPreviewWrap");
    const thumbPreview = document.getElementById("thumbPreview");

    // ============ helper reset ke mode tambah ============
    function resetToCreateMode() {
        form.action = "{{ route('admin.berita.store') }}";
        formTitle.innerText = "Tambah Berita";
        formSubtitle.innerText = "Buat berita baru";

        btnSubmit.innerHTML = '<i class="feather-save me-1"></i> Simpan Berita';

        btnBatal.classList.add("d-none");

        inputJudul.value = "";
        inputPublished.value = "";
        inputActive.checked = true;
        inputThumb.value = "";

        inputIsiHidden.value = "";
        document.querySelector("trix-editor").editor.loadHTML("");

        thumbPreviewWrap.classList.add("d-none");
        thumbPreview.src = "";
    }

    // tombol tambah (reset)
    btnTambah.addEventListener("click", function () {
        resetToCreateMode();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    // tombol batal edit
    btnBatal.addEventListener("click", function () {
        resetToCreateMode();
    });

    // ============ tombol edit dari tabel ============
    document.querySelectorAll(".btnEdit").forEach(function (btn) {
        btn.addEventListener("click", function () {

            const id = this.getAttribute("data-id");
            const judul = this.getAttribute("data-judul");
            const isi = this.getAttribute("data-isi");
            const active = this.getAttribute("data-active");
            const published = this.getAttribute("data-published");
            const thumb = this.getAttribute("data-thumb");

            form.action = "{{ url('admin-banceuy/berita/update') }}/" + id;

            formTitle.innerText = "Edit Berita";
            formSubtitle.innerText = "Update berita yang dipilih";

            btnSubmit.innerHTML = '<i class="feather-refresh-cw me-1"></i> Update Berita';

            btnBatal.classList.remove("d-none");

            inputJudul.value = judul || "";
            inputPublished.value = published || "";
            inputActive.checked = (active == "1");

            // thumbnail preview (tidak bisa set value file)
            if (thumb && thumb.length > 5) {
                thumbPreviewWrap.classList.remove("d-none");
                thumbPreview.src = thumb;
            } else {
                thumbPreviewWrap.classList.add("d-none");
                thumbPreview.src = "";
            }

            // load isi ke trix
            inputIsiHidden.value = isi || "";
            document.querySelector("trix-editor").editor.loadHTML(isi || "");

            window.scrollTo({ top: 0, behavior: "smooth" });
        });
    });

});
</script>
@endsection
