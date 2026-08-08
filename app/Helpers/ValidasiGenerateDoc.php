<?php

namespace App\Helpers;


    public function validateReadyForGenerate($kegiatanId)
    {
        $data = $this->getNarrativeData($kegiatanId);
    
        if ($data['status'] !== 'selesai') {
            throw new \Exception(
                'Laporan masih berstatus draft'
            );
        }
    
        return true;
    }