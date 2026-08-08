<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;

class PemindahanWBPWordGeneratorService
{
    public function generate($data)
    {
        $templatePath =
            storage_path(
                'app/templates/template_pemindahan_wbp.docx'
            );

        if (!file_exists($templatePath)) {
            throw new \Exception(
                'Template Pemindahan WBP tidak ditemukan.'
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
            'jam_tiba',
            $data['jam_tiba'] ?? '-'
        );

        $template->setValue(
            'lokasi',
            $data['lokasi'] ?? '-'
        );

        $template->setValue(
            'lokasi_tujuan',
            $data['lokasi_tujuan'] ?? '-'
        );

        $template->setValue(
            'nomor_surat_izin',
            $data['nomor_surat_izin'] ?? '-'
        );

        $template->setValue(
            'nomor_surat_persetujuan',
            $data['nomor_surat_persetujuan'] ?? '-'
        );

        $template->setValue(
            'nama_wbp',
            $data['nama_wbp'] ?? '-'
        );

        $template->setValue(
            'jumlah_wbp',
            $data['jumlah_wbp'] ?? 1
        );

        $template->setValue(
            'jumlah_petugas',
            $data['jumlah_petugas'] ?? 1
        );

        $template->setValue(
            'jumlah_polisi',
            $data['jumlah_polisi'] ?? 1
        );

        $template->setValue(
            'total_update_wbp',
            $data['total_update_wbp'] ?? 1
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
            !empty($data['foto_kolase']) &&
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
        } else {
            $template->setValue(
                'foto_kolase',
                'FOTO BELUM TERSEDIA'
            );
        }

        $outputDir =
            storage_path(
                'app/public/pemindahan-wbp/laporan'
            );

        if (!file_exists($outputDir)) {
            mkdir(
                $outputDir,
                0777,
                true
            );
        }

        $fileName =
            'Laporan_Pemindahan_WBP_' .
            $data['id'] .
            '.docx';

        $outputPath =
            $outputDir .
            '/' .
            $fileName;

        $template->saveAs($outputPath);

        return $outputPath;
    }
}