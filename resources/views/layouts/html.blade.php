<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page_title')</title>
    <meta name="description" content="@yield('page_description')">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Open Graph -->
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="@yield('page_title')">
    <meta property="og:description" content="@yield('page_description')">
    <meta property="og:image" content="@yield('og_image')">
    <meta property="og:image:width" content="@yield('og_image_width')" />
    <meta property="og:image:height" content="@yield('og_image_height')" />
    <meta property="og:image:title" content="@yield('page_title')" />
    <meta property="og:image:alt" content="@yield('page_description')" />
    <meta property="og:image:type" content="image/png" />


    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="donater.com.ua">
    <meta property="twitter:url" content="https://donater.com.ua/u/aliceinfem">
    <meta name="twitter:title" content="@yield('page_title')">
    <meta name="twitter:description" content="@yield('page_description')">
    <meta name="twitter:image" content="@yield('og_image')">

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    @stack('head-scripts')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-MGFJBVTQCY"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'G-MGFJBVTQCY');
    </script>
</head>
@yield('body')
@guest
    <input type="text" class="form-control hide" id="loginCode" value="{{ session()->get(\App\Http\Controllers\Auth\LoginController::LOGIN_HASH, '') }}">
    <script type="module">
        setInterval(() => {
            $.ajax({
                url: "{{ route('login') }}",
                type: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    loginHash: $('#loginCode').val(),
                },
                success: function (data) {
                    if (window.location.href === '{{ route('login') }}') {
                        window.location.assign(data.url ?? '{{ route('my') }}');
                    }
                    window.location.reload();
                },
            });
        }, 1000);
    </script>
@endguest
</html>
