@extends('startup_view.main')

@section('content')

<style>

/* ================= TITLE ================= */

.page-title{
    text-align:center;
    font-weight:800;
    color:#0f172a;
}

.page-subtitle{
    text-align:center;
    color:#64748b;
    margin-bottom:32px;
}



/* ================= SIDEBAR ================= */

.sidebar-menu{
    background:#69777e;
    padding:14px;
    border-radius:18px;
    position:sticky;
    top:20px;
}

.menu-btn{
    width:100%;
    padding:14px;
    border:none;
    border-radius:14px;
    background:transparent;
    color:white;
    margin-bottom:8px;

    transition:.2s;
}

.menu-btn:hover{
    background:rgba(255,255,255,.08);
}

.menu-btn.active{
    background:white;
    color:#111827;
}



/* ================= MOBILE TAB ================= */

.mobile-tab{
    display:none;
    gap:10px;
    justify-content:center;
    margin-bottom:20px;
}

.tab-btn{
    border:none;
    padding:10px 18px;
    border-radius:999px;
    background:#69777e;
    color:white;

    transition:.2s;
}

.tab-btn.active{
    background:white;
    color:#111827;
}



/* ================= YOUTUBE ================= */

.kegiatan-card{

    background:#69777e;

    border-radius:18px;

    padding:16px;

    color:white;

    height:100%;

}

.kegiatan-video{

    position:relative;

    padding-top:56.25%;

    overflow:hidden;

    border-radius:14px;

    background:black;

}

.kegiatan-video iframe{

    position:absolute;

    inset:0;

    width:100%;

    height:100%;

    border:0;

}



/* ================= SOCIAL ================= */

.social-preview{

    width:100%;

    display:flex;

    flex-direction:column;

    align-items:center;

    height:100%;

}

.social-embed{

    width:100%;

    display:flex;

    justify-content:center;

    align-items:flex-start;

    min-height:400px;

}

.social-embed iframe{

    width:100%;

    border:none;

    min-height:760px;

}



/* ================= INSTAGRAM ================= */

.instagram-media{

    width:100%!important;

    max-width:100%!important;

    min-width:100%!important;

    min-height:760px!important;

    margin:auto!important;

}


/* fallback */

iframe[src*="instagram"]{

    width:100%!important;

    min-height:400px!important;

}



/* ================= TIKTOK ================= */

.tiktok-embed{

    width:100%!important;

    max-width:100%!important;

    min-width:unset!important;

    min-height:920px!important;

    margin:auto!important;

}


/* fallback */

iframe[src*="tiktok"]{

    width:100%!important;

    min-height:920px!important;

}



/* ================= INFO ================= */

.video-info{

    text-align:center;

    margin-top:14px;

}

.video-info strong{

    display:block;

    font-size:16px;

    color:#0f172a;

}

.video-info small{

    color:#64748b;

}



/* ================= EMPTY ================= */

.empty-state{

    background:white;

    border-radius:20px;

    padding:34px;

    text-align:center;

}



/* ================= RESPONSIVE ================= */

@media(max-width:991px){

.social-embed{

min-height:650px;

}

.instagram-media{

min-height:400px!important;

}

.tiktok-embed{

min-height:760px!important;

}

iframe[src*="instagram"]{

min-height:650px!important;

}

iframe[src*="tiktok"]{

min-height:760px!important;

}

}



@media(max-width:768px){

.sidebar-menu{

display:none;

}

.mobile-tab{

display:flex;

}


.social-embed{

min-height:580px;

}

.social-embed iframe{

min-height:580px;

}


.instagram-media{

min-height:620px!important;

}

.tiktok-embed{

min-height:700px!important;

}

iframe[src*="instagram"]{

min-height:620px!important;

}

iframe[src*="tiktok"]{

min-height:700px!important;

}

}

</style>



<div class="container my-4">

<h2 class="page-title">
KEGIATAN LAPAS BANCEUY
</h2>

<p class="page-subtitle">
Dokumentasi video kegiatan pembinaan
</p>


<div class="row">

{{-- SIDEBAR --}}
<div class="col-lg-3">

<div class="sidebar-menu">

<button class="menu-btn active"
data-tab="YOUTUBE"
onclick="switchView('YOUTUBE')">
YouTube
</button>

<button class="menu-btn"
data-tab="INSTAGRAM"
onclick="switchView('INSTAGRAM')">
Instagram
</button>

<button class="menu-btn"
data-tab="TIKTOK"
onclick="switchView('TIKTOK')">
TikTok
</button>

</div>

</div>


{{-- CONTENT --}}
<div class="col-lg-9">

<div class="mobile-tab">

<button class="tab-btn active"
data-tab="YOUTUBE"
onclick="switchView('YOUTUBE')">
YouTube
</button>

<button class="tab-btn"
data-tab="INSTAGRAM"
onclick="switchView('INSTAGRAM')">
Instagram
</button>

<button class="tab-btn"
data-tab="TIKTOK"
onclick="switchView('TIKTOK')">
TikTok
</button>

</div>


@foreach(['YOUTUBE','INSTAGRAM','TIKTOK'] as $platform)

<div
id="panel-{{ $platform }}"
class="tab-content"
style="{{ $platform==='YOUTUBE' ? '' : 'display:none' }}">

<div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 align-items-start">

@forelse($videos[$platform] as $item)

<div class="col">

@if($platform==='YOUTUBE')

<div class="kegiatan-card">

<div class="kegiatan-video">

{!! $item->video_embed !!}

</div>

<div class="video-info">

<strong>
{{ $item->judul }}
</strong>

<small>
{{ $item->published_at?->format('d F Y') }}
</small>

</div>

</div>

@else

<div class="social-preview">

<div class="social-embed">

{!! $item->video_embed !!}

</div>

<div class="video-info">

<strong>
{{ $item->judul }}
</strong>

<small>
{{ $item->published_at?->format('d F Y') }}
</small>

</div>

</div>

@endif

</div>

@empty

<div class="col-12">

<div class="empty-state">

Tidak ada video

</div>

</div>

@endforelse

</div>

</div>

@endforeach

</div>

</div>

</div>


<script>

function refreshEmbed(){

if(window.instgrm){
window.instgrm.Embeds.process();
}

if(window.tiktokEmbedLoad){
window.tiktokEmbedLoad();
}

}

function switchView(platform){

document
.querySelectorAll('.tab-content')
.forEach(
e=>e.style.display='none'
);

document
.getElementById(
'panel-'+platform
)
.style.display='block';

document
.querySelectorAll(
'.menu-btn,.tab-btn'
)
.forEach(
b=>b.classList.remove('active')
);

document
.querySelectorAll(
`[data-tab="${platform}"]`
)
.forEach(
b=>b.classList.add('active')
);

setTimeout(
refreshEmbed,
300
);

}

window.onload=refreshEmbed;

</script>

@endsection