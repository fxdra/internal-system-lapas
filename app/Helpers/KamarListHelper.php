<?php

namespace App\Helpers;

function buildKamarList(array $kamar)
{
    $jumlah = count($kamar);

    if ($jumlah == 1) {
        return $kamar[0];
    }

    if ($jumlah == 2) {
        return $kamar[0] . ' dan ' . $kamar[1];
    }

    $terakhir = array_pop($kamar);

    return implode(', ', $kamar) . ', dan ' . $terakhir;
}