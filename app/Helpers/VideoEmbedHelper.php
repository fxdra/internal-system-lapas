<?php

namespace App\Helpers;

class VideoEmbedHelper
{
    public static function make(string $url): string
    {
        $url = trim($url);

        if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
            return self::fallback();
        }

        $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');

        /*
        |--------------------------------------------------------------------------
        | YOUTUBE
        |--------------------------------------------------------------------------
        */
        if (
            preg_match(
                '~(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{11})~',
                $url,
                $m
            )
        ) {

            return self::wrapIframe(
                'https://www.youtube-nocookie.com/embed/' .
                $m[1] .
                '?rel=0&playsinline=1'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | INSTAGRAM
        |--------------------------------------------------------------------------
        */
        if (
            str_contains($host, 'instagram.com')
        ) {

            if (
                preg_match(
                    '~instagram\.com/(?:p|reel)/([a-zA-Z0-9_-]+)~',
                    $url
                )
            ) {

                $embed =
                    rtrim($url, '/') . '/embed';

                return self::wrapIframe(
                        rtrim($url, '/') . '/embed'
                    );
            }

            return self::fallback();
        }

        /*
        |--------------------------------------------------------------------------
        | TIKTOK
        |--------------------------------------------------------------------------
        */
        if (
            str_contains($host, 'tiktok.com')
        ) {

            if (
                preg_match(
                    '~tiktok\.com/.+/video/(\d+)~',
                    $url
                )
            ) {

                return '
                    <blockquote
                        class="tiktok-embed"
                        cite="' . $url . '"
                        style="max-width:100%;">
                    </blockquote>

                    <script async
                    src="https://www.tiktok.com/embed.js">
                    </script>
                ';
            }

            return self::fallback();
        }

        return self::fallback();
    }

    /*
    |--------------------------------------------------------------------------
    | IFRAME WRAPPER
    |--------------------------------------------------------------------------
    */
    private static function wrapIframe(
        string $src
    ): string {

        return '
        <div class="video-wrapper">

            <iframe
                src="' . $src . '"
                loading="lazy"
                allowfullscreen
                frameborder="0"
                allow="
                    autoplay;
                    encrypted-media;
                    picture-in-picture;
                    clipboard-write
                ">
            </iframe>

        </div>';
    }

    /*
    |--------------------------------------------------------------------------
    | FALLBACK
    |--------------------------------------------------------------------------
    */
    private static function fallback(): string
    {
        return '
        <div
            style="
                height:100%;
                display:flex;
                align-items:center;
                justify-content:center;
                color:#777;
                padding:20px;
            ">

            Video tidak dapat ditampilkan

        </div>';
    }
}