@extends('admin-banceuy.partisi.main')

@section('content')
    <style>
        .diagnosis-header h4 {
            color: #283144;
            font-size: 20px;
            letter-spacing: -.2px;
        }

        .diagnosis-subtitle {
            color: #7a8496;
            font-size: 13px;
        }

        .diagnosis-stat,
        .diagnosis-table-card {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 14px;
        }

        .diagnosis-stat {
            padding: 18px 20px;
        }

        .diagnosis-stat-label {
            color: #6c757d;
            font-size: 13px;
        }

        .diagnosis-stat-value {
            color: #3454d1;
            font-size: 27px;
            font-weight: 700;
            line-height: 1.25;
        }

        .diagnosis-table-card {
            overflow: hidden;
        }

        .diagnosis-table {
            margin-bottom: 0;
        }

        .diagnosis-table thead th {
            background: #f8f9fc;
            color: #596579;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .3px;
            text-transform: uppercase;
            white-space: nowrap;
            border-bottom: 1px solid #e9ecef;
            padding: 13px 16px;
        }

        .diagnosis-table tbody td {
            color: #394357;
            font-size: 13px;
            padding: 13px 16px;
            vertical-align: middle;
        }

        .diagnosis-code {
            color: #3454d1;
            font-weight: 700;
            white-space: nowrap;
        }

        .diagnosis-category {
            display: inline-block;
            border-radius: 7px;
            padding: 4px 8px;
            background: rgba(52, 84, 209, .09);
            color: #5b7efd;
            font-size: 11px;
        }

        .diagnosis-pagination .page-link {
            color: #3454d1;
        }

        .diagnosis-pagination .page-item.active .page-link {
            background: #3454d1;
            border-color: #3454d1;
            color: #fff;
        }

        .diagnosis-category-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            z-index: 1050;
            max-height: 240px;
            overflow-y: auto;
            margin-top: 4px;
            border: 1px solid var(--bs-border-color, #dee2e6);
            border-radius: 8px;
            background: var(--bs-body-bg, #fff);
            color: var(--bs-body-color, #212529);
            box-shadow: 0 6px 18px rgba(0, 0, 0, .15);
        }

        .diagnosis-category-option {
            display: block;
            width: 100%;
            padding: 9px 12px;
            border: 0;
            background: transparent;
            color: inherit;
            text-align: left;
            cursor: pointer;
            overflow-wrap: anywhere;
        }

        .diagnosis-category-option:hover,
        .diagnosis-category-option:focus {
            background: var(--bs-secondary-bg, #e9ecef);
            outline: none;
        }

        .diagnosis-category-empty {
            padding: 10px 12px;
            color: var(--bs-secondary-color, #6c757d);
            font-size: .875rem;
        }

        html.app-skin-dark #diagnosis-kategori-search {
            background-color: #202938;
            color: #e2e8f0;
            border-color: #354052;
        }

        html.app-skin-dark #diagnosis-kategori-search::placeholder {
            color: #a7b1c2;
        }

        html.app-skin-dark .diagnosis-category-results {
            background: #202938;
            color: #e2e8f0;
            border-color: #354052;
        }

        html.app-skin-dark .diagnosis-category-option:hover,
        html.app-skin-dark .diagnosis-category-option:focus {
            background: #354052;
        }

        html.app-skin-dark .diagnosis-category-empty {
            color: #a7b1c2;
        }

        html.app-skin-dark .diagnosis-stat,
        html.app-skin-dark .diagnosis-table-card {
            background: #202938;
            border-color: #354052;
        }

        html.app-skin-dark .diagnosis-header h4,
        html.app-skin-dark .diagnosis-stat-value,
        html.app-skin-dark .diagnosis-table tbody td {
            color: #e2e8f0;
        }

        html.app-skin-dark .diagnosis-subtitle,
        html.app-skin-dark .diagnosis-stat-label {
            color: #a7b1c2;
        }

        html.app-skin-dark .diagnosis-table thead th {
            background: #283345;
            color: #d5dcea;
            border-color: #354052;
        }

        html.app-skin-dark .diagnosis-table tbody td {
            border-color: #354052;
        }

        html.app-skin-dark .diagnosis-pagination .page-link {
            background: #202938;
            border-color: #354052;
            color: #d5dcea;
        }

        html.app-skin-dark .diagnosis-pagination .page-item.active .page-link {
            background: #3454d1;
            color: #fff;
        }

        @media (max-width: 767px) {
            .diagnosis-toolbar>* {
                width: 100%;
            }

            .diagnosis-toolbar .btn {
                width: 100%;
            }
        }
    </style>

    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 diagnosis-header">
            <div>
                <h4 class="mb-1">Master Diagnosis</h4>
            </div>
            <span class="badge bg-light text-primary border px-3 py-2">ICD-10</span>
        </div>

        <div class="diagnosis-stat mb-4">
            <div class="diagnosis-stat-label mb-1">Data Diagnosis</div>
            <div class="diagnosis-stat-value">{{ number_format($totalDiagnosis, 0, ',', '.') }}</div>
            <div class="diagnosis-subtitle mt-1">Data tidak dapat ditambah, diedit, atau dihapus dari halaman ini.</div>
        </div>

        <div class="diagnosis-table-card">
            <div class="p-3 p-lg-4 border-bottom">
                <form method="GET" action="{{ route('klinik.diagnosis.index') }}"
                    class="d-flex flex-wrap align-items-end gap-3 diagnosis-toolbar">

                    <div style="width: 900px; max-width: 100%; position: relative;">
                        <label for="diagnosis-kategori-search" class="form-label small fw-semibold">
                            Kategori Diagnosis
                        </label>

                        <input type="text" id="diagnosis-kategori-search" class="form-control"
                            placeholder="Pilih atau cari kategori..." autocomplete="off" value="{{ $kategori }}">

                        <input type="hidden" id="diagnosis-kategori" name="kategori" value="{{ $kategori }}">

                        <div id="diagnosis-kategori-results" class="diagnosis-category-results" style="display: none;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="feather-search me-1"></i> Cari
                    </button>

                    @if ($kategori !== '')
                        <a href="{{ route('klinik.diagnosis.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            <div class="table-responsive">
                <table class="table diagnosis-table table-hover align-middle">
                    <thead>
                        <tr>
                            <th style="width: 70px;">No.</th>
                            <th style="width: 180px;">Kode Diagnosis</th>
                            <th>Uraian</th>
                            <th style="width: 220px;">Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($diagnoses as $diagnosis)
                            <tr>
                                <td class="text-muted">{{ $diagnoses->firstItem() + $loop->index }}</td>
                                <td><span class="diagnosis-code">{{ $diagnosis->kode_sakit }}</span></td>
                                <td>{{ $diagnosis->uraian }}</td>
                                <td>
                                    @if ($diagnosis->kategori)
                                        <span class="diagnosis-category">{{ $diagnosis->kategori }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <i class="feather-search d-block mb-2" style="font-size: 24px;"></i>
                                    <div class="fw-semibold">Diagnosis tidak ditemukan</div>
                                    <div class="text-muted small mt-1">Coba ubah kata kunci atau filter kategori.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($diagnoses->hasPages())
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-3 p-lg-4 border-top">
                    <small class="text-muted">
                        Menampilkan {{ $diagnoses->firstItem() }}–{{ $diagnoses->lastItem() }} dari
                        {{ number_format($diagnoses->total(), 0, ',', '.') }} data hasil.
                    </small>
                    <div class="diagnosis-pagination">{{ $diagnoses->links() }}</div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('diagnosis-kategori-search');
            const hidden = document.getElementById('diagnosis-kategori');
            const results = document.getElementById('diagnosis-kategori-results');

            if (!input || !hidden || !results) return;

            const endpoint = @json(route('klinik.diagnosis.categories'));

            let debounceTimer;
            let controller;
            let requestNumber = 0;

            function closeResults() {
                results.style.display = 'none';
                results.replaceChildren();
            }

            function showMessage(message) {
                results.replaceChildren();

                const item = document.createElement('div');
                item.className = 'diagnosis-category-empty';
                item.textContent = message;

                results.appendChild(item);
                results.style.display = 'block';
            }

            function selectCategory(category) {
                input.value = category;
                hidden.value = category === 'Semua kategori' ? '' : category;
                closeResults();
            }

            function renderResults(categories) {
                results.replaceChildren();

                const allOption = document.createElement('button');
                allOption.type = 'button';
                allOption.className = 'diagnosis-category-option';
                allOption.textContent = 'Semua kategori';
                allOption.addEventListener('click', function() {
                    selectCategory('Semua kategori');
                });
                results.appendChild(allOption);

                if (!categories.length) {
                    showMessage('Tidak ada kategori yang cocok.');
                    return;
                }

                categories.forEach(function(category) {
                    const option = document.createElement('button');
                    option.type = 'button';
                    option.className = 'diagnosis-category-option';
                    option.textContent = category;

                    option.addEventListener('click', function() {
                        selectCategory(category);
                    });

                    results.appendChild(option);
                });

                results.style.display = 'block';
            }

            input.addEventListener('focus', function() {
                if (input.value.trim().length < 2) {
                    showMessage('Ketik minimal 2 karakter untuk mencari kategori.');
                }
            });

            input.addEventListener('input', function() {
                clearTimeout(debounceTimer);

                if (controller) controller.abort();

                hidden.value = '';

                const keyword = input.value.trim();
                const currentRequest = ++requestNumber;

                if (keyword.length < 2) {
                    closeResults();

                    if (keyword.length === 0) {
                        hidden.value = '';
                    } else {
                        showMessage('Ketik minimal 2 karakter.');
                    }

                    return;
                }

                debounceTimer = setTimeout(async function() {
                    controller = new AbortController();
                    showMessage('Mencari kategori...');

                    try {
                        const url = new URL(endpoint, window.location.origin);
                        url.searchParams.set('q', keyword);

                        const response = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            signal: controller.signal
                        });

                        if (!response.ok) {
                            throw new Error('Gagal mengambil kategori.');
                        }

                        const categories = await response.json();

                        if (currentRequest !== requestNumber) return;

                        renderResults(categories);
                    } catch (error) {
                        if (error.name === 'AbortError') return;

                        if (currentRequest === requestNumber) {
                            showMessage('Pencarian gagal. Coba lagi.');
                        }
                    }
                }, 300);
            });

            input.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') closeResults();

                if (event.key === 'Enter' && results.style.display === 'block') {
                    const firstOption = results.querySelector('button');

                    if (firstOption) {
                        event.preventDefault();
                        firstOption.click();
                    }
                }
            });

            document.addEventListener('click', function(event) {
                if (!input.parentElement.contains(event.target)) {
                    closeResults();
                }
            });
        });
    </script>
@endsection
