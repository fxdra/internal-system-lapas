<?php

namespace App\Services;

use PhpOffice\PhpWord\TemplateProcessor;

class WordGeneratorService
{
    public function generate(
        array $data,
        array $narasi
    ): string {

        $templatePath = storage_path(
            'app/templates/template_razia.docx'
        );

        if (!file_exists($templatePath)) {
            throw new \Exception(
                'Template Word tidak ditemukan.'
            );
        }

        $template = new TemplateProcessor(
            $templatePath
        );

        $template->setValue(
            'status',
            $data['status'] ?? ''
        );

        $template->setValue(
            'hari_razia',
            $data['hari_razia'] ?? ''
        );

        $template->setValue(
            'tanggal_razia',
            $data['tanggal_razia'] ?? ''
        );

        $template->setValue(
            'jam_mulai',
            $data['jam_mulai'] ?? ''
        );

        $template->setValue(
            'jam_selesai',
            $data['jam_selesai'] ?? ''
        );

        $template->setValue(
            'lokasi',
            $data['lokasi'] ?? ''
        );

        $template->setValue(
            'pimpinan_razia',
            $data['pimpinan_razia'] ?? ''
        );

        $template->setValue(
            'jumlah_kamar',
            $data['jumlah_kamar'] ?? 0
        );

        $template->setValue(
            'jumlah_barang_temuan',
            $data['jumlah_barang_temuan'] ?? 0
        );

        $template->setValue(
            'jumlah_total_temuan',
            $data['jumlah_total_temuan'] ?? 0
        );

        $template->setValue(
            'nama_kplp',
            $data['nama_kplp'] ?? ''
        );

        $template->setValue(
            'nama_kasi_kamtib',
            $data['nama_kasi_kamtib'] ?? ''
        );

        $template->setValue(
            'nama_kalapas',
            $data['nama_kalapas'] ?? ''
        );

        foreach ($narasi as $key => $value) {

            $template->setValue($key,$value);
        }

        if (!empty($data['foto'][0])) {

            $fotoPath = storage_path(
                'app/public/razia/' .
                $data['foto'][0]
            );

            if (file_exists($fotoPath)) {

                $template->setImageValue(
                    'foto_kolase',
                    [
                        'path' => $fotoPath,
                        'width' => 450,
                        'height' => 250,
                        'ratio' => true,
                    ]
                );
            }
        }


        $folderOutput = storage_path(
            'app/laporan'
        );

        if (!file_exists($folderOutput)) {

            mkdir(
                $folderOutput,
                0777,
                true
            );
        }

        $outputFile =
            $folderOutput .
            DIRECTORY_SEPARATOR .
            $data['nama_file_laporan'];

        $template->saveAs(
            $outputFile
        );

        return $outputFile;
    }
}
