<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;

class RaziaGabunganWordGeneratorService
{
    public function generate($data)
    {
        $templatePath =
            storage_path(
                'app/templates/template_razia_gabungan.docx'
            );

        if (!file_exists($templatePath)) {

            throw new \Exception(
                'Template DOCX tidak ditemukan.'
            );
        }

        $template =
            new TemplateProcessor($templatePath);
        
        $template->setValue(
            'hari_tanggal', 
            $data['hari_tanggal'] ?? '-'
            );
        
        $template->setValue(
            'jam_mulai',
            $data['jam_mulai'] ?? '-'
        );

        $template->setValue(
            'jam_selesai',
            $data['jam_selesai'] ?? '-'
        );
        
        $template->setValue(
            'lokasi', 
            $data['lokasi'] ?? '-'
        );
        
        $template->setValue(
            'nomor_surat_dirjen', 
            $data['nomor_surat_dirjen']
        );
        
        $template->setValue(
            'tanggal_surat_dirjen', 
            $data['nomor_surat_dirjen'] ?? '-'
        );
        
        $template->setValue(
            'nomor_keputusan_menteri',
            $data['nomor_keputusan_menteri'] ?? '-'
        );
        
        $template->setValue(
            'tahun_keputusan_menteri',
            $data['tahun_keputusan_menteri'] ?? '-'
        );
        
        $template->setValue(
            'nama_arahan_menteri', 
            $data['nama_arahan_menteri'] ?? '-'
        );
        
        $template->setValue(
            'tanggal_arahan_menteri',
            $data['tanggal_arahan_menteri'] ?? '-'
        );
        
        $template->setValue(
            'daftar_kamar',
            $data['daftar_kamar'] ?? '-'
        );
        
        $template->setValue(
            'barang_temuan',
            $data['barang_temuan'] ?? '-'
        );
        
        $template->setValue(
            'jumlah_pegawai_tes_urine',
            $data['jumlah_pegawai_tes_urine'] ?? '0'
        );
        
        $template->setValue(
            'jumlah_wbp_tes_urine',
            $data['jumlah_wbp_tes_urine'] ?? '0'
        );
        
        $template->setValue(
            'hasil_pegawai_tes_urine',
            $data['hasil_pegawai_tes_urine'] ?? '-'
        );
        
        $template->setValue(
            'hasil_wbp_tes_urine',
            $data['hasil_wbp_tes_urine'] ?? '-'
        );
        
        $template->setValue(
            'nama_kplp',
            $data['nama_kplp'] ?? '-'
        );
        
        $template->setValue(
            'nama_kasi_kamtib',
            $data['nama_kasi_kamtib'] ?? '-'
        );
        
        $template->setValue(
            'nama_kalapas',
            $data['nama_kalapas'] ?? '-'
        );

        if (
            !empty($data['foto_kolase'])
            &&
            file_exists($data['foto_kolase'])
        ) {

            $template->setImageValue(
                'foto_kolase',
                [
                    'path' => $data['foto_kolase'],
                    'width' => 450,
                    'height' => 250,
                    'ratio' => true
                ]
            );
        }

        $filename =
            'razia_gabungan_' .
            time() .
            '.docx';

        $outputPath =
            storage_path(
                'app/laporan/' .
                $filename
            );


        $folder =
            storage_path(
                'app/laporan/'
            );

        if (!file_exists($folder)) {

            mkdir(
                $folder,
                0777,
                true
            );
        }

        $template->saveAs($outputPath);

        return $outputPath;
    }
}