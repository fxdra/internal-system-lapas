<?php

namespace App\Services;

use Carbon\Carbon;
use Riskihajar\Terbilang\Facades\Terbilang;

class RaziaNarrativeService
{
    public function generate(array $data)
    {
        return [
            'poin1' => $this->poin1($data),
            'poin2' => $this->poin2($data),
            'poin3' => $this->poin3(),
            'poin4' => $this->poin4($data),
            'poin5' => $this->poin5($data),
            'poin6' => $this->poin6($data),
            'poin7' => $this->poin7(),
            'poin8' => $this->poin8(),
        ];
    }
    
    private function joinKalimat(array $items): string
    {
        $count = count($items);

        if ($count === 0) {
            return '';
        }

        if ($count === 1) {
            return $items[0];
        }

        if ($count === 2) {
            return $items[0] . ' dan ' . $items[1];
        }

        $last = array_pop($items);

        return implode(', ', $items)
            . ', dan '
            . $last;
    }

    /**
     * POIN 1
     */
    public function poin1(array $data)
    {
        return sprintf(
            'Pada hari %s, %s pukul %s WIB s.d. selesai telah dilaksanakan kegiatan razia insidentil dan penggeledahan kamar hunian di Blok Hunian %s yang meliputi kamar %s di Lapas Kelas IIA Banceuy Bandung. Kegiatan dilaksanakan sebagai langkah deteksi dini gangguan keamanan dan ketertiban serta upaya pencegahan terhadap peredaran barang-barang terlarang di dalam lingkungan Lapas.',
            $this->hariIndonesia($data['tanggal_razia']),
            $this->tanggalIndonesia($data['tanggal_razia']),
            date('H.i', strtotime($data['jam_mulai'])),
            $this->listBlok($data['daftar_blok']),
            $this->listKamar($data['daftar_kamar'])
        );
    }

    /**
     * POIN 2
     */
    public function poin2(array $data)
    {
    $personel = [];

    /*
    |--------------------------------------------------------------------------
    | STAFF KPLP
    |--------------------------------------------------------------------------
    */

    if ($data['staff_kplp'] > 0) {

        $personel[] =
            $data['staff_kplp'] .
            ' orang Staff KPLP';
    }

    /*
    |--------------------------------------------------------------------------
    | PETUGAS PIKET
    |--------------------------------------------------------------------------
    */

    if ($data['petugas_piket_jumlah'] > 0) {

        $personel[] =
            $data['petugas_piket_jumlah'] .
            ' orang ' .
            $data['petugas_piket_jabatan'];
    }

    /*
    |--------------------------------------------------------------------------
    | REGU PENGAMANAN
    |--------------------------------------------------------------------------
    */

    if ($data['regu_pengamanan'] > 0) {

        $personel[] =
            $data['regu_pengamanan'] .
            ' orang Regu Pengamanan';
    }

    /*
    |--------------------------------------------------------------------------
    | GABUNG PERSONEL
    |--------------------------------------------------------------------------
    */

     $textPersonel =
            $this->joinKalimat($personel);


    return
        'Razia dipimpin oleh ' .
        $data['pimpinan_razia'] .
        ' dan diikuti oleh ' .
        $textPersonel .
        ' dengan tetap mengedepankan pendekatan humanis, persuasif, serta sesuai Standar Operasional Prosedur (SOP) yang berlaku.';
}

    /**
     * POIN 3
     */
    public function poin3()
    {
        return 'Sebelum pelaksanaan kegiatan, petugas melaksanakan apel dan arahan singkat terkait teknis pelaksanaan razia, pembagian tugas, serta penekanan terhadap kewaspadaan dan keselamatan petugas selama kegiatan berlangsung.';
    }

    /**
     * POIN 4
     */
    public function poin4(array $data)
    {
        return sprintf(
            'Petugas kemudian melakukan pemeriksaan dan penggeledahan pada kamar hunian warga binaan di Blok %s yang meliputi kamar %s secara menyeluruh, meliputi pemeriksaan badan, barang pribadi, lemari, tempat tidur, kamar mandi, serta sudut-sudut kamar yang dianggap rawan digunakan untuk menyimpan barang terlarang.',
            $this->listBlok($data['daftar_blok']),
            $this->listKamar($data['daftar_kamar'])
        );
    }

    /**
     * POIN 5
     */
    public function poin5(array $data)
    {
    if (empty($data['barang_temuan'])) {
        return 'Dalam pelaksanaan razia tidak ditemukan barang-barang terlarang maupun benda yang berpotensi mengganggu keamanan dan ketertiban (nihil).';
    }

        return 'Dalam pelaksanaan razia, petugas berhasil menemukan beberapa barang larangan yang berpotensi mengganggu keamanan dan ketertiban berupa '
            . $this->listBarang($data['barang_temuan'])
            . '.';
    }

    /**
     * POIN 6
     */
    public function poin6(array $data)
    {
    if (empty($data['barang_temuan'])) {
        return 'Tidak terdapat barang hasil razia yang memerlukan pengamanan maupun inventarisasi lebih lanjut.';
    }

    return 'Selanjutnya barang hasil razia diamankan untuk dilakukan pendataan dan inventarisasi lebih lanjut sesuai dengan ketentuan yang berlaku.';
    }

    /**
     * POIN 7
     */
    public function poin7()
    {
        return 'Selama kegiatan berlangsung situasi dalam keadaan aman, tertib, dan kondusif serta seluruh warga binaan kooperatif mengikuti jalannya kegiatan razia dan penggeledahan.';
    }

    /**
     * POIN 8
     */
    public function poin8()
    {
        return 'Kegiatan razia insidentil ini merupakan bentuk komitmen Lapas Kelas IIA Banceuy Bandung dalam mendukung program akselerasi Menteri Imigrasi dan Pemasyarakatan serta implementasi Zero Halinar (Handphone, Pungli, dan Narkoba).';
    }

    /**
     * FORMAT HARI
     */
    private function hariIndonesia($tanggal)
    {
        Carbon::setLocale('id');

        return Carbon::parse($tanggal)
            ->translatedFormat('l');
    }

    /**
     * FORMAT TANGGAL
     */
    private function tanggalIndonesia($tanggal)
    {
        Carbon::setLocale('id');

        return Carbon::parse($tanggal)
            ->translatedFormat('d F Y');
    }
    /**
     * LIST BLOK
     */
    private function listBlok(array $blok)
    {
        $jumlah = count($blok);

        if ($jumlah === 1) {
        return $blok[0];
        }

        if ($jumlah === 2) {
        return $blok[0] . ' dan ' . $blok[1];
        }

        $last = array_pop($blok);

        return implode(', ', $blok) . ', dan ' . $last;
    }

    /**
     * LIST KAMAR
     */
    private function listKamar(array $kamar)
    {
        $jumlah = count($kamar);

        if ($jumlah === 1) {
            return $kamar[0];
        }

        if ($jumlah === 2) {
            return $kamar[0] . ' dan ' . $kamar[1];
        }

        $last = array_pop($kamar);

        return implode(', ', $kamar) . ', dan ' . $last;
    }

    /**
     * LIST BARANG TEMUAN
     */
    private function listBarang(array $items)
    {
        if (empty($items)) {
            return 'nihil';
        }
    
        $hasil = [];
    
        foreach ($items as $item) {
    
             $hasil[] =
                $item['jumlah']
                . ' (' . strtolower(Terbilang::make($item['jumlah'])) . ') '
                . $item['satuan']
                . ' '
                . ucwords(strtolower($item['nama_barang']));
        }
    
        $jumlah = count($hasil);
    
        if ($jumlah === 1) {
            return $hasil[0];
        }
    
        if ($jumlah === 2) {
            return $hasil[0] . ' dan ' . $hasil[1];
        }
    
        $last = array_pop($hasil);
    
        return implode(', ', $hasil) . ', dan ' . $last;
    }
    
    public function poin5Summary(array $data)
        {
            if ($data['jumlah_total_temuan'] == 0) {
                return 'Nihil';
            }
        
            return $data['jumlah_total_temuan']
                . ' ('
                . strtolower(\Riskihajar\Terbilang\Facades\Terbilang::make(
                    $data['jumlah_total_temuan']
                ))
                . ')';
        }
}