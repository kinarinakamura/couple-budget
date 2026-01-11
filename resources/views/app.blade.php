<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="/favicon.ico">

        <!-- Primary Meta Tags -->
        <meta name="title" content="ふたり家計簿 - 30秒で分かる理想の分担方法">
        <meta name="description" content="同棲カップル向けの家計分担計算ツール。年収や生活費から公平な分担方法を30秒で診断。グラフで見やすく、SNSで簡単シェア。">
        <meta name="keywords" content="家計簿,同棲,カップル,分担,生活費,計算,診断">

        <!-- Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=G-0BFT1R1DXS"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
          gtag('config', 'G-XXXXXXXXXX');
        </script>

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ config('app.url') }}">
        <meta property="og:title" content="ふたり家計簿 - 30秒で分かる理想の分担方法">
        <meta property="og:description" content="同棲カップル向けの家計分担計算ツール。年収や生活費から公平な分担方法を30秒で診断。">
        <!-- <meta property="og:image" content="{{ config('app.url') }}/images/ogp.png"> -->
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ config('app.url') }}">
        <meta property="twitter:title" content="ふたり家計簿 - 30秒で分かる理想の分担方法">
        <meta property="twitter:description" content="同棲カップル向けの家計分担計算ツール。年収や生活費から公平な分担方法を30秒で診断。">
        <!-- <meta property="twitter:image" content="{{ config('app.url') }}/images/ogp.png"> -->

        <!-- Google Fonts: Kosugi Maru -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Kosugi+Maru&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>