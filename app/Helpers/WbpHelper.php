<?php

namespace App\Helpers;

class WbpHelper
{
    /**
     * Normalisasi No Registrasi Instansi
     *
     * Contoh:
     * B I 283 / 26   -> BI 283/26
     * BI 201 /26     -> BI 201/26
     * BI201/26       -> BI 201/26
     * BI  202 / 26   -> BI 202/26
     * BI.305/26      -> BI 305/26
     * BI. 305 / 26   -> BI 305/26
     */
    public static function normalizeNoReg(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '') {
            return null;
        }

        // =========================
        // NORMALISASI WHITESPACE
        // =========================

        $value = preg_replace('/\s+/', ' ', $value);

        // =========================
        // HURUF KAPITAL
        // =========================

        $value = strtoupper($value);

        // =========================
        // NORMALISASI PREFIX BI
        // =========================
        //
        // B I 283/26 -> BI 283/26
        // BI.283/26  -> BI 283/26
        // BI . 283/26 -> BI 283/26
        //

        $value = preg_replace(
            '/^B\s*I\s*\.\s*/',
            'BI ',
            $value
        );

        $value = preg_replace(
            '/^B\s+I\s*/',
            'BI ',
            $value
        );

        $value = preg_replace(
            '/^BI\s*\.\s*/',
            'BI ',
            $value
        );

        // =========================
        // NORMALISASI SPASI SEKITAR /
        // =========================

        $value = preg_replace(
            '/\s*\/\s*/',
            '/',
            $value
        );

        // =========================
        // NORMALISASI FORMAT BI + ANGKA
        // =========================
        //
        // BI201/26  -> BI 201/26
        // BI 201/26 -> BI 201/26
        //

        $value = preg_replace(
            '/^BI\s*(\d+)\/(\d+)$/',
            'BI $1/$2',
            $value
        );

        return trim($value);
    }
}
