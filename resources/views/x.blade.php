<!DOCTYPE html>
<html lang="id">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1">

<title>Agent Registration</title>

<meta name="csrf-token"
      content="{{ csrf_token() }}">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet">

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
      rel="stylesheet">

<style>

:root{
    --primary:#2563eb;
    --primary-dark:#1d4ed8;
    --success:#16a34a;
    --danger:#dc2626;
    --bg:#f8fafc;
}

body{
    min-height:100vh;
    background:#f8fafc;
    overflow-x:hidden;
    font-family:Inter,sans-serif;
}

/* =========================
   FLOATING BACKGROUND
========================= */

.bg-shape{
    position:fixed;
    border-radius:50%;
    filter:blur(100px);
    opacity:.20;
    z-index:-1;
}

.shape-1{
    width:350px;
    height:350px;
    background:#2563eb;
    top:-100px;
    left:-100px;
    animation:floatOne 10s infinite ease-in-out;
}

.shape-2{
    width:350px;
    height:350px;
    background:#60a5fa;
    bottom:-100px;
    right:-100px;
    animation:floatTwo 12s infinite ease-in-out;
}

@keyframes floatOne{
    50%{
        transform:translateY(-40px);
    }
}

@keyframes floatTwo{
    50%{
        transform:translateY(40px);
    }
}

/* =========================
   CARD
========================= */

.register-card{
    border:none;
    border-radius:24px;
    overflow:hidden;
    box-shadow:
    0 15px 50px rgba(0,0,0,.08);
    animation:fadeUp .6s ease;
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(25px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

/* =========================
   LEFT PANEL
========================= */

.left-side{
    background:
    linear-gradient(
        135deg,
        #2563eb,
        #1e40af
    );

    color:white;
    padding:50px;
    height:100%;
}

.logo-circle{
    width:80px;
    height:80px;
    border-radius:50%;
    background:rgba(255,255,255,.15);

    display:flex;
    align-items:center;
    justify-content:center;

    font-size:35px;
}

.left-title{
    margin-top:30px;
    font-size:34px;
    font-weight:700;
}

.left-desc{
    margin-top:15px;
    opacity:.9;
    line-height:1.7;
}

.feature-list{
    margin-top:30px;
}

.feature-item{
    margin-bottom:12px;
}

/* =========================
   FORM
========================= */

.form-wrapper{
    padding:45px;
}

.form-title{
    font-weight:700;
}

.form-subtitle{
    color:#64748b;
}

.required::after{
    content:" *";
    color:red;
}

.form-control{
    height:54px;
    border-radius:12px;
}

.form-control:focus{
    transform:translateY(-2px);
    transition:.2s;
}

.input-group-text{
    border-radius:12px;
}

/* =========================
   PASSWORD STRENGTH
========================= */

.strength{
    margin-top:10px;
    height:8px;
    background:#e5e7eb;
    border-radius:30px;
    overflow:hidden;
}

.strength-bar{
    width:0%;
    height:100%;
    transition:.3s;
}

#strengthText{
    margin-top:6px;
    font-size:13px;
}

/* =========================
   GPS CARD
========================= */

.gps-card{
    border:1px dashed #cbd5e1;
    border-radius:12px;
    padding:15px;
    background:#f8fafc;
}

.gps-label{
    color:#64748b;
    font-size:13px;
}

/* =========================
   BUTTON
========================= */

.btn-register{
    height:56px;
    border-radius:12px;
    border:none;
    background:#2563eb;
    font-weight:600;
}

.btn-register:hover{
    background:#1d4ed8;
    transform:translateY(-2px);
}

.btn-gps{
    border-radius:12px;
}

/* =========================
   TOAST
========================= */

.toast-container{
    z-index:999999;
}

/* =========================
   MOBILE
========================= */

@media(max-width:991px){

    .left-side{
        display:none;
    }

    .form-wrapper{
        padding:25px;
    }

}

</style>

</head>

<body>

<div class="bg-shape shape-1"></div>
<div class="bg-shape shape-2"></div>

<div class="toast-container position-fixed top-0 end-0 p-3">

    <div id="liveToast"
         class="toast border-0">

        <div class="toast-header">
            <strong class="me-auto">
                Notification
            </strong>

            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="toast">
            </button>
        </div>

        <div class="toast-body"
             id="toastMessage">
        </div>

    </div>

</div>

<div class="container py-5">

<div class="row justify-content-center">

<div class="col-xl-10">

<div class="card register-card">

<div class="row g-0">

<div class="col-lg-5">

<div class="left-side">

<div class="logo-circle">
    <i class="fa-solid fa-shield-halved"></i>
</div>

<div class="left-title">
    Agent Portal
</div>

<p class="left-desc">
    Secure enterprise registration system
    with GPS verification, strong password
    validation and API integration.
</p>

<div class="feature-list">

    <div class="feature-item">
        <i class="fa-solid fa-location-dot"></i>
        GPS Verification
    </div>

    <div class="feature-item">
        <i class="fa-solid fa-lock"></i>
        Secure Authentication
    </div>

    <div class="feature-item">
        <i class="fa-solid fa-shield"></i>
        Enterprise Security
    </div>

</div>

</div>

</div>

<div class="col-lg-7">

<div class="form-wrapper">

<h2 class="form-title">
    Register Agent
</h2>

<p class="form-subtitle mb-4">
    Create your enterprise account
</p>

<form id="registerForm">

    @csrf

    <div class="row">

        <div class="col-12 mb-3">

            <label class="form-label required">
                Username
            </label>

            <input
                type="text"
                class="form-control"
                id="username"
                name="username"
                autocomplete="off"
                spellcheck="false"
                maxlength="30">

            <div class="invalid-feedback"></div>

        </div>

        <div class="col-12 mb-3">

            <label class="form-label">
                Email
            </label>

            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                autocomplete="off">

            <div class="invalid-feedback"></div>

        </div>

        <div class="col-12 mb-3">

            <label class="form-label">
                Phone
            </label>

            <input
                type="text"
                class="form-control"
                id="phone"
                name="phone"
                autocomplete="off">

            <div class="invalid-feedback"></div>

        </div>

        <div class="col-12 mb-3">

            <label class="form-label required">
                Password
            </label>

            <div class="input-group">

                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    autocomplete="new-password">

                <button
                    type="button"
                    class="input-group-text"
                    id="togglePassword">

                    <i class="fa-solid fa-eye"></i>

                </button>

            </div>

            <div class="strength">

                <div
                    class="strength-bar"
                    id="strengthBar">
                </div>

            </div>

            <div id="strengthText"></div>

            <div class="invalid-feedback d-block"></div>

            <small class="text-muted">
                Minimal 8 karakter,
                huruf besar,
                huruf kecil,
                dan angka.
            </small>

        </div>

    </div>

    <div class="gps-card mt-4">

        <div class="d-flex justify-content-between align-items-center">

            <strong>
                GPS Verification
            </strong>

            <span
                id="gpsStatus"
                class="badge bg-danger">

                Belum Diverifikasi

            </span>

        </div>

        <hr>

        <div class="row">

            <div class="col-md-4 mb-2">

                <div class="gps-label">
                    Latitude
                </div>

                <div id="latPreview">
                    -
                </div>

            </div>

            <div class="col-md-4 mb-2">

                <div class="gps-label">
                    Longitude
                </div>

                <div id="lngPreview">
                    -
                </div>

            </div>

            <div class="col-md-4 mb-2">

                <div class="gps-label">
                    Accuracy
                </div>

                <div id="accPreview">
                    -
                </div>

            </div>

        </div>

        <input
            type="hidden"
            id="latitude"
            name="latitude">

        <input
            type="hidden"
            id="longitude"
            name="longitude">

        <input
            type="hidden"
            id="accuracy"
            name="accuracy">
            
            <input type="hidden"
       name="device_fingerprint"
       id="device_fingerprint">


        <button
            type="button"
            id="btnGps"
            class="btn btn-outline-primary btn-gps w-100 mt-3">

            <i class="fa-solid fa-location-crosshairs me-2"></i>

            Ambil Lokasi GPS

        </button>

    </div>

    <div
        id="serverErrors"
        class="alert alert-danger mt-3 d-none">
    </div>

    <button
        type="submit"
        id="btnRegister"
        class="btn btn-primary btn-register w-100 mt-4">

        <span id="btnText">
            Register Agent
        </span>

    </button>

</form>

</div>

</div>

</div>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

const form = document.getElementById('registerForm');

const username = document.getElementById('username');
const email = document.getElementById('email');
const phone = document.getElementById('phone');
const password = document.getElementById('password');

const latitude = document.getElementById('latitude');
const longitude = document.getElementById('longitude');
const accuracy = document.getElementById('accuracy');

const btnGps = document.getElementById('btnGps');
const btnRegister = document.getElementById('btnRegister');
const btnText = document.getElementById('btnText');

const strengthBar = document.getElementById('strengthBar');
const strengthText = document.getElementById('strengthText');

const gpsStatus = document.getElementById('gpsStatus');

const latPreview = document.getElementById('latPreview');
const lngPreview = document.getElementById('lngPreview');
const accPreview = document.getElementById('accPreview');

const serverErrors = document.getElementById('serverErrors');

const csrfToken =
document
.querySelector('meta[name="csrf-token"]')
.getAttribute('content');

const toastElement =
document.getElementById('liveToast');

const toast =
new bootstrap.Toast(toastElement);

function showToast(message)
{
    document
    .getElementById('toastMessage')
    .innerHTML = message;

    toast.show();
}

/*
|--------------------------------------------------------------------------
| PASSWORD TOGGLE
|--------------------------------------------------------------------------
*/

document
.getElementById('togglePassword')
.addEventListener('click', function(){

    if(password.type === 'password')
    {
        password.type = 'text';

        this.innerHTML =
        '<i class="fa-solid fa-eye-slash"></i>';
    }
    else
    {
        password.type = 'password';

        this.innerHTML =
        '<i class="fa-solid fa-eye"></i>';
    }

});

/*
|--------------------------------------------------------------------------
| PASSWORD STRENGTH
|--------------------------------------------------------------------------
*/

password.addEventListener('input', function(){

    let score = 0;

    if(this.value.length >= 8)
        score++;

    if(/[A-Z]/.test(this.value))
        score++;

    if(/[a-z]/.test(this.value))
        score++;

    if(/[0-9]/.test(this.value))
        score++;

    let percent = score * 25;

    strengthBar.style.width =
    percent + '%';

    switch(score)
    {
        case 1:

            strengthBar.style.background =
            '#dc2626';

            strengthText.innerHTML =
            'Weak';

        break;

        case 2:

            strengthBar.style.background =
            '#f59e0b';

            strengthText.innerHTML =
            'Medium';

        break;

        case 3:

            strengthBar.style.background =
            '#3b82f6';

            strengthText.innerHTML =
            'Strong';

        break;

        case 4:

            strengthBar.style.background =
            '#16a34a';

            strengthText.innerHTML =
            'Very Strong';

        break;

        default:

            strengthBar.style.width =
            '0%';

            strengthText.innerHTML =
            '';
    }

});

/*
|--------------------------------------------------------------------------
| GPS
|--------------------------------------------------------------------------
*/

btnGps.addEventListener(
'click',
getLocation
);

function getLocation()
{
    if(!navigator.geolocation)
    {
        showToast(
        'Browser tidak mendukung GPS'
        );

        return;
    }

    gpsStatus.className =
    'badge bg-warning';

    gpsStatus.innerHTML =
    'Mengambil Lokasi...';

    navigator.geolocation.getCurrentPosition(

        function(position){

            latitude.value =
            position.coords.latitude;

            longitude.value =
            position.coords.longitude;

            accuracy.value =
            position.coords.accuracy;

            latPreview.innerHTML =
            position.coords.latitude;

            lngPreview.innerHTML =
            position.coords.longitude;

            accPreview.innerHTML =
            Math.round(
            position.coords.accuracy
            ) + ' meter';

            gpsStatus.className =
            'badge bg-success';

            gpsStatus.innerHTML =
            'Verified';

            showToast(
            'GPS berhasil diperoleh'
            );

        },

        function(error){

            gpsStatus.className =
            'badge bg-danger';

            gpsStatus.innerHTML =
            'Gagal';

            showToast(
            'Gagal mengambil GPS'
            );

        },

        {
            enableHighAccuracy:true,
            timeout:15000,
            maximumAge:0
        }

    );
}

/*
|--------------------------------------------------------------------------
| VALIDATION
|--------------------------------------------------------------------------
*/

function clearValidation()
{
    document
    .querySelectorAll('.is-invalid')
    .forEach(el => {

        el.classList.remove(
        'is-invalid'
        );

    });

    serverErrors.classList.add(
    'd-none'
    );

    serverErrors.innerHTML = '';
}

function setInvalid(field, message)
{
    field.classList.add(
    'is-invalid'
    );

    let feedback =
    field.parentElement
    .querySelector('.invalid-feedback');

    if(feedback)
    {
        feedback.innerHTML =
        message;
    }
}

function validateForm()
{
    clearValidation();

    let valid = true;

    const usernameRegex =
    /^[A-Za-z0-9_]+$/;

    if(
        username.value.length < 5 ||
        username.value.length > 30
    )
    {
        setInvalid(
        username,
        'Username harus 5-30 karakter'
        );

        valid = false;
    }

    if(
        !usernameRegex.test(
        username.value
        )
    )
    {
        setInvalid(
        username,
        'Hanya huruf, angka dan underscore'
        );

        valid = false;
    }

    if(
        email.value &&
        !/^[^\s@]+@[^\s@]+\.[^\s@]+$/
        .test(email.value)
    )
    {
        setInvalid(
        email,
        'Format email tidak valid'
        );

        valid = false;
    }

    if(
        phone.value &&
        !/^[0-9]{8,15}$/
        .test(phone.value)
    )
    {
        setInvalid(
        phone,
        'Nomor telepon harus 8-15 digit'
        );

        valid = false;
    }

    const pass =
    password.value;

    if(
        pass.length < 8 ||
        !/[A-Z]/.test(pass) ||
        !/[a-z]/.test(pass) ||
        !/[0-9]/.test(pass)
    )
    {
        setInvalid(
        password,
        'Password tidak memenuhi syarat'
        );

        valid = false;
    }

    if(
        !latitude.value ||
        !longitude.value ||
        !accuracy.value
    )
    {
        showToast(
        'Silakan ambil GPS terlebih dahulu'
        );

        valid = false;
    }

    return valid;
}

/*
|--------------------------------------------------------------------------
| SUBMIT
|--------------------------------------------------------------------------
*/

form.addEventListener(
'submit',
async function(e){

    e.preventDefault();

    if(!validateForm())
    {
        return;
    }

    btnRegister.disabled = true;

    btnText.innerHTML = `
    <span class="spinner-border spinner-border-sm me-2"></span>
    Creating Account...
    `;

    try{

        const formData =
        new FormData(form);

        const response =
        await fetch(
        '/api/register-backoffice',
        {
            method:'POST',
            headers:{
                'Accept':
                'application/json',

                'X-CSRF-TOKEN':
                csrfToken
            },
            body:formData
        });

        const result =
        await response.json();

        if(response.ok)
        {
            showToast(
            'Register berhasil'
            );

            setTimeout(function(){

                window.location.href =
                '/login';

            },1500);

            return;
        }

        if(response.status === 422)
        {
            if(result.errors)
            {
                Object.keys(
                result.errors
                ).forEach(function(key){

                    const field =
                    document.querySelector(
                    `[name="${key}"]`
                    );

                    if(field)
                    {
                        setInvalid(
                        field,
                        result.errors[key][0]
                        );
                    }

                });
            }
        }
        else
        {
            serverErrors.classList.remove(
            'd-none'
            );

            serverErrors.innerHTML =
            result.message ??
            'Terjadi kesalahan';
        }

    }
    catch(error)
    {
        console.error(error);

        showToast(
        'Koneksi ke server gagal'
        );
    }
    finally
    {
        btnRegister.disabled = false;

        btnText.innerHTML =
        'Register Agent';
    }

});

</script>

<script>

async function generateFingerprint()
{
    const raw = [
        navigator.userAgent,
        navigator.language,
        screen.width,
        screen.height,
        Intl.DateTimeFormat().resolvedOptions().timeZone,
        navigator.platform,
        navigator.hardwareConcurrency
    ].join('|');

    const encoder = new TextEncoder();

    const data = encoder.encode(raw);

    const hash = await crypto.subtle.digest(
        'SHA-256',
        data
    );

    const hashArray =
        Array.from(
      