@extends('admin-banceuy.partisi.main')

@section('content')

<link rel="stylesheet"
      href="https://uicdn.toast.com/editor/latest/toastui-editor.min.css">

<style>
.toastui-editor-defaultUI{
    border-radius:16px !important;
    overflow:hidden;
    border:1px solid #dee2e6 !important;
}

.toastui-editor-toolbar{
    background:#fff !important;
}

.toastui-editor-contents{
    font-size:14px;
}

/* GLOBAL */
.product-card .card{
    border:0;
    border-radius:16px;
    transition:.25s ease;
    height:100%;
    background:#fff;
    padding:14px;
    overflow:visible;
}

.product-card .card:hover{
    transform:translateY(-4px);
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.product-item{
    display:flex;
    gap:12px;
    align-items:flex-start;
}

/* IMAGE */
.product-image-wrapper{
    flex:0 0 90px;
    height:90px;
    border-radius:12px;
    overflow:hidden;
    position:relative;
    background:#f3f3f3;
    display:flex;
    align-items:center;
    justify-content:center;
}

.product-image-wrapper::before{
    content:"";
    position:absolute;
    inset:0;
    background-image:var(--img);
    background-size:cover;
    background-position:center;
    filter:blur(14px);
    transform:scale(1.2);
    opacity:.35;
}

.product-image{
    position:relative;
    z-index:2;
    width:100%;
    height:100%;
    object-fit:contain;
    padding:6px;
}

.product-content{
    flex:1;
    display:flex;
    flex-direction:column;
    justify-content:space-between;
    min-height:90px;
}

.product-title{font-size:13px;font-weight:700;}
.product-meta{font-size:11px;color:#777;}
.product-price{font-size:13px;font-weight:700;}
.badge-status{font-size:10px;padding:5px 8px;}

.btn-action{
    border-radius:10px;
    font-size:12px;
    font-weight:600;
}

/* MODAL */
.custom-modal{
    position:fixed;
    inset:0;
    z-index:999999;
    display:none;
    align-items:center;
    justify-content:center;
    padding:20px;
}

.custom-modal.active{display:flex;}

.custom-modal-overlay{
    position:absolute;
    inset:0;
    background:rgba(0,0,0,.6);
}

.custom-modal-box{
    position:relative;
    width:100%;
    max-width:700px;
    max-height:90vh;
    overflow-y:auto;
    background:#fff;
    border-radius:24px;
    padding:24px;
    z-index:2;
}

.custom-modal-close{
    position:absolute;
    top:14px;
    right:14px;
    width:38px;
    height:38px;
    border:none;
    border-radius:50%;
    background:#f3f3f3;
}
</style>

<div class="container-fluid py-4">

    <div class="mb-4">
        <h3 class="fw-bold">Product Management</h3>
        <p class="text-muted">Manage categories and products efficiently.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">

        {{-- CATEGORY --}}
        <div class="col-lg-4">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5>Create Category</h5>

                    <form action="{{ url('/admin-banceuy/categories') }}" method="POST">
                        @csrf

                        <input type="text"
                               name="name"
                               class="form-control mb-3 text-uppercase"
                               required>

                        <select name="parent_id" class="form-select mb-3">
                            <option value="">No Parent</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>

                        <button class="btn btn-dark w-100">SAVE CATEGORY</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- PRODUCT --}}
        <div class="col-lg-8">
            <div class="card shadow-sm rounded-4">
                <div class="card-body">
                    <h5>Create Product</h5>

                    <form action="{{ url('/admin-banceuy/produk') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">

                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control text-uppercase" required>
                            </div>

                            <div class="col-md-6">
                                <select name="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div id="editor"></div>
                                <input type="hidden" name="description" id="description">
                            </div>

                            <div class="col-md-4">
                                <input type="number" name="price" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <input type="number" name="stock" class="form-control" required>
                            </div>

                            <div class="col-md-4">
                                <input type="number" name="weight" class="form-control">
                            </div>

                            <div class="col-md-6">
                                <select name="status" class="form-select" required>
                                    <option value="draft">DRAFT</option>
                                    <option value="published">PUBLISHED</option>
                                    <option value="archived">ARCHIVED</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <input type="file" name="image" class="form-control">
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100">SAVE PRODUCT</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    {{-- LIST --}}
    <div class="card mt-4 shadow-sm rounded-4">
        <div class="card-header d-flex justify-content-between">
            <b>Product List</b>
            <input id="liveSearch" class="form-control w-25" placeholder="Search">
        </div>

        <div class="card-body">
            <div class="row g-3">

                @foreach($products as $product)

                    <div class="col-6 col-md-4 col-lg-3 product-card">

                        <div class="card">

                            <div class="product-item">

                                <div class="product-image-wrapper"
                                     style="--img:url('{{ $product->image ? asset('storage/'.$product->image) : '' }}')">

                                    <img src="{{ $product->image ? asset('storage/'.$product->image) : '' }}"
                                         class="product-image">

                                </div>

                                <div class="product-content">

                                    <div>
                                        <div class="product-title">{{ strtoupper($product->name) }}</div>
                                        <div class="product-meta">{{ $product->category->name ?? '-' }}</div>
                                        <div class="product-price">Rp {{ number_format($product->price) }}</div>
                                        <div class="product-meta">STOCK : {{ $product->stock }}</div>
                                    </div>

                                    <div class="mt-2">
                                        <span class="badge badge-status">
                                            {{ strtoupper($product->status) }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                            <div class="d-flex gap-2 mt-3">

                                <button class="btn btn-outline-primary btn-sm w-50 btn-detail"
                                    data-name="{{ $product->name }}"
                                    data-desc="{{ base64_encode($product->description) }}"
                                    data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}"
                                    data-weight="{{ $product->weight }}"
                                    data-status="{{ $product->status }}">

                                    Detail
                                </button>

                                <button class="btn btn-dark btn-sm w-50 btn-edit"
                                    data-id="{{ $product->id }}"
                                    data-name="{{ $product->name }}"
                                    data-desc="{{ base64_encode($product->description) }}"
                                    data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}"
                                    data-weight="{{ $product->weight }}"
                                    data-status="{{ $product->status }}">

                                    Edit
                                </button>

                            </div>

                        </div>

                    </div>

                @endforeach

            </div>
        </div>
    </div>

</div>

{{-- DETAIL MODAL --}}
<div class="custom-modal" id="detailModal">
    <div class="custom-modal-overlay"></div>
    <div class="custom-modal-box">

        <button class="custom-modal-close close-modal">✕</button>

        <h4>Product Detail</h4>

        <h5 id="d_name"></h5>
        <p id="d_desc"></p>

        <p><b>Price:</b> <span id="d_price"></span></p>
        <p><b>Stock:</b> <span id="d_stock"></span></p>
        <p><b>Weight:</b> <span id="d_weight"></span></p>
        <p><b>Status:</b> <span id="d_status"></span></p>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://uicdn.toast.com/editor/latest/toastui-editor-all.min.js"></script>

<script>
const editor = new toastui.Editor({
    el: document.querySelector('#editor'),
    height: '350px',
    initialEditType: 'wysiwyg',
    previewStyle: 'vertical'
});

$('form').on('submit', function () {
    $('#description').val(editor.getHTML());
});

/* =========================
DETAIL MODAL (FIXED 100%)
========================= */
$(document).on('click', '.btn-detail', function () {

    let desc = $(this).data('desc');

    // decode base64 → HTML asli
    desc = atob(desc);

    $('#d_name').text($(this).data('name'));
    $('#d_desc').html(desc);
    $('#d_price').text($(this).data('price'));
    $('#d_stock').text($(this).data('stock'));
    $('#d_weight').text($(this).data('weight'));
    $('#d_status').text($(this).data('status'));

    $('#detailModal').addClass('active');
    $('body').css('overflow', 'hidden');
});

/* CLOSE MODAL */
$(document).on('click', '.close-modal, .custom-modal-overlay', function () {
    $('.custom-modal').removeClass('active');
    $('body').css('overflow', 'auto');
});

/* SEARCH */
document.getElementById("liveSearch").addEventListener("input", function () {

    let val = this.value.toLowerCase();

    document.querySelectorAll(".product-card").forEach(c => {

        let name = c.querySelector(".product-title")?.innerText.toLowerCase() || "";

        c.style.display = name.includes(val) ? "" : "none";
    });
});
</script>

@endsection