<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WbpFotoBridgeDownload extends Command
{
    protected $signature = 'wbp:download-foto';
    protected $description = 'Bridge downloader untuk foto WBP dari server lama';

    public function handle()
    {
        $apiUrl = "https://lapasbanceuykplp.web.id/api/wbp-pending";

        $data = json_decode(file_get_contents($apiUrl), true);

        if (!$data) {
            $this->error("Tidak ada data dari API");
            return;
        }

        foreach ($data as $item) {

            $url = $item['foto_wbp'] ?? null;

            if (!$url) continue;

            $this->info("Download: " . $url);

            try {

                $image = @file_get_contents($url);

                if ($image) {

                    $fileName = uniqid() . ".jpg";

                    // simpan ke storage Laravel
                    file_put_contents(
                        storage_path("app/public/foto_wbp/" . $fileName),
                        $image
                    );

                    // kirim balik update ke Laravel API
                    $updateUrl = "https://lapasbanceuykplp.web.id/api/update-foto"
                        . "?id=" . $item['id']
                        . "&file=foto_wbp/" . $fileName;

                    @file_get_contents($updateUrl);

                    $this->info("OK: " . $fileName);

                } else {
                    $this->error("Gagal download: " . $url);
                }

            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }

        $this->info("SELESAI");
    }
}