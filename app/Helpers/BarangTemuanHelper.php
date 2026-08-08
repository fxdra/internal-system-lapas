<?php

namespace App\Helpers;


function buildBarangTemuan(array $items)
{
    $hasil = [];

    foreach ($items as $item) {

        $hasil[] =
            $item['jumlah']
            .' ('.terbilang($item['jumlah']).') '
            .$item['satuan'].' '
            .strtolower($item['barang']);
    }

    $jumlah = count($hasil);

    if ($jumlah == 1) {
        return $hasil[0];
    }

    if ($jumlah == 2) {
        return $hasil[0].' dan '.$hasil[1];
    }

    $last = array_pop($hasil);

    return implode(', ', $hasil)
           .', dan '.$last;
}