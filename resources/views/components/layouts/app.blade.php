<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Security headers -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    
    <title>سُكنة - نبض المدن ومفاتيحها | اكتشف وحدتك المثالية</title>
    <meta name="description" content="تطبيق سُكنة يوفر لك أحدث المشاريع العقارية في السعودية. تصفح آلاف الوحدات السكنية، احجز مباشرة، واستفد من عروض حصرية. حمّل التطبيق الآن!">
    <meta name="keywords" content="تطبيق عقارات, سكن في السعودية, شقق للبيع, فلل للايجار, مشاريع عقارية, شراء منزل, سُكنة, sukna">
    <meta name="author" content="سُكنة">

    <!-- Open Graph / Facebook Meta Tags -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://sukna.app/">
    <meta property="og:title" content="سُكنة - نبض المدن ومفاتيحها">
    <meta property="og:description" content="اكتشف وحدتك المثالية في أرقى المشاريع العقارية بالمملكة عبر تطبيق سُكنة">
    <meta property="og:image" content="{{ asset('frontend/assets/img/sukna-15.jpg') }}">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="https://sukna.app/">
    <meta name="twitter:title" content="سُكنة - نبض المدن ومفاتيحها">
    <meta name="twitter:description" content="اكتشف وحدتك المثالية في أرقى المشاريع العقارية بالمملكة عبر تطبيق سُكنة">
    <meta name="twitter:image" content="{{ asset('frontend/assets/img/sukna-15.jpg') }}">
    <meta name="twitter:site" content="@sukna_app">

    <!-- Canonical URL -->
    <link rel="canonical" href="https://sukna.app/">

    <!-- Favicon and App Icons -->
    <link rel="shortcut icon" href="{{ asset('frontend/assets/img/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('frontend/assets/img/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('frontend/assets/img/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/assets/img/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('frontend/assets/img/site.webmanifest') }}">


    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans+Arabic:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('frontend/assets/img/favicon.svg') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/colors/navy.css') }}">
    <link rel="preload" href="{{ asset('frontend/assets/css/fonts/ibm.css') }}" as="style" onload="this.rel='stylesheet'">
    <!-- Schema.org markup for Google -->
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "سُكنة",
        "url": "https://sukna.app",
        "logo": "https://sukna.app/assets/img/logodark2.svg",
        "description": "نبض المدن ومفاتيحها لاكتشاف وحدات سكنية في أرقى المشاريع العقارية",
        "sameAs": [
            "https://twitter.com/sukna_app",
            "https://www.facebook.com/suknaapp",
            "https://www.instagram.com/suknaapp"
        ]
        }
    </script>

        <!-- Snap Pixel Code -->
        <script type='text/javascript'>
        (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
        {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
        a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
        r.src=n;var u=t.getElementsByTagName(s)[0];
        u.parentNode.insertBefore(r,u);})(window,document,
        'https://sc-static.net/scevent.min.js');

        snaptr('init', '0c89270f-3114-458a-9a55-084f4fdcdee7', {});

        snaptr('track', 'PAGE_VIEW');

        </script>
        <!-- End Snap Pixel Code -->
            
        <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-FBFHKK5MXZ"></script>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'G-FBFHKK5MXZ');
    </script>
        <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-PC3CCCF2');</script>
    <!-- End Google Tag Manager -->


    <!-- Alpine.js hide -->
    <style>[x-cloak] { display: none !important; }</style>

    @filamentStyles
    @vite('resources/css/app.css')
    @livewireStyles

</head>
<body>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-PC3CCCF2"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->

    <div class="content-wrapper">
        @livewire('partials.frontend.navbar')
        {{ $slot }}
        @livewire('partials.frontend.footer')

    </div>

    @livewire('notifications')

    <script src="{{ asset('frontend/assets/js/plugins.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/theme.js') }}"></script>
    
    <!-- Scripts -->
    @filamentScripts
    @vite(['resources/js/app.js'])
    @vite(['resources/js/session-expired.js'])
    @livewireScripts
</body>
</html>
