<?php

namespace App\Helpers;

use Carbon\Carbon;

function hariIndonesia($tanggal)
{
    Carbon::setLocale('id');

    return Carbon::parse($tanggal)
        ->translatedFormat('l');
}

function tanggalIndonesia($tanggal)
{
    Carbon::setLocale('id');

    return Carbon::parse($tanggal)
        ->translatedFormat('d F Y');
}