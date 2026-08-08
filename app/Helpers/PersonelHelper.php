<?php

namespace App\Helpers;

function buildPersonel(array $personel)
{
    $hasil = [];

    foreach ($personel as $item) {

        $hasil[] =
            $item['jumlah']
            .' orang '
            .$item['label'];
    }

    $last = array_pop($hasil);

    return implode(', ', $hasil)
           .' dan '
           .$last;
}