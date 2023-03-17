<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="apple-touch-icon" sizes="180x180" href="/public/pwa/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/public/pwa/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/public/pwa/favicon-16x16.png">
    <link rel="manifest" href="/public/pwa/site.webmanifest">
    <link rel="mask-icon" href="/public/pwa/safari-pinned-tab.svg" color="#765ff7">
    <link rel="shortcut icon" href="/public/pwa/favicon.ico">
    <meta name="theme-color" content="#f8f7fd">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;800;900&display=swap" rel="stylesheet">
    <title>{{ $title ?? 'Linkedout' }}</title>
    @yield('head')
</head>
<body>
@include('components.navbar')
@yield('content')
</body>
</html>
