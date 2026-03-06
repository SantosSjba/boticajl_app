<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>
            (function() {
                try {
                    var theme = localStorage.getItem('theme');
                    var systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    var isDark = theme === 'dark' || (!theme && systemDark);
                    if (document.documentElement) {
                        if (isDark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                } catch (_) {}
            })();
        </script>
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@100..900&display=swap" rel="stylesheet" />

        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        <script>
            (function() {
                try {
                    if (document.documentElement.classList.contains('dark')) {
                        document.body.classList.add('dark', 'bg-gray-900');
                    } else {
                        document.body.classList.remove('dark', 'bg-gray-900');
                    }
                } catch (_) {}
            })();
        </script>
        @inertia
    </body>
</html>
