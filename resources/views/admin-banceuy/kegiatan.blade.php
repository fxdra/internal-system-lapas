@extends('admin-banceuy.partisi.main')

@section('content')
    <div class="container-fluid my-3">

        {{-- ================= HEADER ================= --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h4 class="fw-bold mb-1">Kegiatan</h4>
                <small class="text-muted">Kelola kegiatan lapas (video)</small>
            </div>
        </div>

        {{-- ================= ALERT ================= --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="fw-semibold mb-1">Ada error:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ================= FORM ================= --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                    <div>
                        <h5 class="fw-bold mb-0" id="formTitle">Tambah Kegiatan</h5>
                        <small class="text-muted" id="formSubtitle">Tambah kegiatan baru</small>
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

                <form id="formKegiatan" method="POST" action="{{ route('admin.kegiatan.store') }}">
                    @csrf
                
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-4">
                
                            {{-- HEADER FORM --}}
                            <div class="mb-4">
                                <h5 class="fw-bold mb-1">Form Kegiatan</h5>
                                <small class="text-muted">Isi data kegiatan dengan lengkap</small>
                            </div>
                
                            <div class="row g-4">
                
                                {{-- JUDUL --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Judul Kegiatan</label>
                                    <input type="text"
                                           name="judul"
                                           id="judul"
                                           class="form-control form-control-lg"
                                           placeholder="Contoh: Kegiatan Pembinaan WBP"
                                           required>
                                </div>
                
                                {{-- PLATFORM + TANGGAL (1 ROW) --}}
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Platform</label>
                                    <select name="platform" class="form-select form-control-lg" required>
                                        <option value="YOUTUBE">YouTube</option>
                                        <option value="INSTAGRAM">Instagram</option>
                                        <option value="TIKTOK">TikTok</option>
                                    </select>
                                </div>
                
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tanggal Publish</label>
                                    <input type="datetime-local"
                                           name="published_at"
                                           id="published_at"
                                           class="form-control form-control-lg">
                                </div>
                
                                {{-- LINK VIDEO --}}
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Link Video</label>
                                    <input type="url"
                                           name="video_url"
                                           id="video_url"
                                           class="form-control form-control-lg"
                                           placeholder="https://youtube.com / instagram / tiktok"
                                           required>
                                    <small class="text-muted">Support YouTube, Instagram, dan TikTok</small>
                                </div>
                
                                {{-- PREVIEW --}}
                                <div class="col-12 d-none" id="videoPreviewWrap">

                                    <label class="form-label fw-semibold">
                                        Preview Video
                                    </label>
                                
                                    <div
                                        id="videoPreviewBox"
                                        class="border rounded-4 overflow-hidden shadow-sm p-2">
                                
                                        <div id="videoPreview"></div>
                                
                                    </div>
                                
                                </div>
                
                                {{-- STATUS --}}
                                <div class="col-12">
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded-4 bg-light">
                                        <div>
                                            <div class="fw-semibold">Status Kegiatan</div>
                                            <small class="text-muted">Aktifkan agar tampil di publik</small>
                                        </div>
                
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="is_active"
                                                   id="is_active"
                                                   value="1"
                                                   checked>
                                        </div>
                                    </div>
                                </div>
                
                            </div>
                
                            {{-- BUTTON --}}
                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <button type="reset" class="btn btn-light rounded-pill px-4">
                                    Reset
                                </button>
                
                                <button type="submit" class="btn btn-success rounded-pill px-4" id="btnSubmit">
                                    <i class="feather-save me-1"></i> Simpan Kegiatan
                                </button>
                            </div>
                
                        </div>
                    </div>
                </form>

            </div>
        </div>

        {{-- ================= TABLE ================= --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width:60px;">No</th>
                                <th>Judul</th>
                                <th style="width:240px;">Preview</th>
                                <th style="width:140px;">Status</th>
                                <th style="width:170px;">Publish</th>
                                <th style="width:220px;" class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($kegiatans as $i => $item)
                                <tr>
                                    <td class="text-muted">{{ $kegiatans->firstItem() + $i }}</td>

                                    <td>
                                        <div class="fw-semibold">{{ $item->judul }}</div>
                                        <div class="text-muted small">/{{ $item->slug }}</div>
                                    </td>
                                    <td>
                                        @if(strtoupper($item->platform)==='INSTAGRAM')
    
                                            <div class="preview-instagram">
                                            
                                                {!! $item->video_embed !!}
                                            
                                            </div>
                                            
                                            @else
                                            
                                            <div
                                            class="ratio ratio-16x9
                                            rounded-3
                                            overflow-hidden
                                            border">
                                            
                                                {!! $item->video_embed !!}
                                            
                                            </div>
                                        
                                        @endif
                                    </td>

                                    <td>
                                        @if ($item->is_active)
                                            <span
                                                class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Aktif</span>
                                        @else
                                            <span
                                                class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td class="text-muted small">
                                        {{ $item->published_at?->format('d M Y H:i') ?? '-' }}
                                    </td>

                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">

                                            <button type="button" class="btn btn-warning btn-sm rounded-pill px-3 btnEdit"
                                                data-id="{{ $item->id }}" data-judul="{{ e($item->judul) }}"
                                                data-video="{{ e($item->video_url) }}"
                                                data-embed="{{ e($item->video_embed) }}"
                                                data-active="{{ $item->is_active ? 1 : 0 }}"
                                                data-published="{{ $item->published_at?->format('Y-m-d\TH:i') }}">
                                                <i class="feather-edit me-1"></i>Edit
                                            </button>

                                            <form action="{{ route('admin.kegiatan.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus kegiatan ini?')">
                                                @csrf
                                                <button class="btn btn-danger btn-sm rounded-pill px-3">
                                                    <i class="feather-trash me-1"></i>Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-dark">
                                        Belum ada kegiatan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            @if ($kegiatans->hasPages())
                <div class="card-footer bg-white border-0">
                    {{ $kegiatans->links() }}
                </div>
            @endif
        </div>

    </div>

    {{-- ================= JS ================= --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const form = document.getElementById("formKegiatan");
            const btnTambah = document.getElementById("btnTambah");
            const btnBatal = document.getElementById("btnBatal");
            const btnSubmit = document.getElementById("btnSubmit");

            const inputJudul = document.getElementById("judul");
            const inputVideo = document.getElementById("video_url");
            const inputPublished = document.getElementById("published_at");
            const inputActive = document.getElementById("is_active");

            const previewWrap = document.getElementById("videoPreviewWrap");
            const preview = document.getElementById("videoPreview");

            function resetForm() {
                form.action = "{{ route('admin.kegiatan.store') }}";
                inputJudul.value = "";
                inputVideo.value = "";
                inputPublished.value = "";
                inputActive.checked = true;
                previewWrap.classList.add("d-none");
                preview.innerHTML = "";
                btnBatal.classList.add("d-none");
                btnSubmit.innerHTML = '<i class="feather-save me-1"></i> Simpan Kegiatan';
            }

            btnTambah.onclick = resetForm;
            btnBatal.onclick = resetForm;

            document.querySelectorAll(".btnEdit").forEach(btn => {
                btn.onclick = function() {

                    form.action = "{{ url('admin-banceuy/kegiatan/update') }}/" + this.dataset.id;

                    inputJudul.value = this.dataset.judul;
                    inputVideo.value = this.dataset.video;
                    inputPublished.value = this.dataset.published || "";
                    inputActive.checked = this.dataset.active == "1";

                    previewWrap.classList.remove("d-none");
                    preview.innerHTML = this.dataset.embed;

                    btnBatal.classList.remove("d-none");
                    btnSubmit.innerHTML = '<i class="feather-refresh-cw me-1"></i> Update Kegiatan';

                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                };
            });

        });
    </script>
@endsection
