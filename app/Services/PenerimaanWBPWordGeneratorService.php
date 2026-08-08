<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;

class PenerimaanWBPWordGeneratorService
{
    public function generate($data)
    {
        $templatePath =
            storage_path(
                'app/templates/template_penerimaan_wbp.docx'
            );

        if (!file_exists($templatePath)) {
            throw new \Exception(
                'Template Penerimaan WBP tidak ditemukan.'
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
            'lokasi',
            $data['lokasi'] ?? '-'
        );

        $template->setValue(
            'nomor_surat_dirjenpas',
            $data['nomor_surat_dirjenpas'] ?? '-'
        );

        $template->setValue(
            'tanggal_surat_dirjenpas',
            $data['tanggal_surat_dirjenpas'] ?? '-'
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
            'asal_upt',
            $data['asal_upt'] ?? '-'
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
                'app/public/penerimaan-wbp/laporan'
            );

        if (!file_exists($outputDir)) {
            mkdir(
                $outputDir,
                0777,
                true
            );
        }

        $fileName =
            'Laporan_Penerimaan_WBP_' .
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