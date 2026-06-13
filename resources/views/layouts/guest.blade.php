<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', '一行日記') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-diary-bg text-diary-text">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-10">
            <div class="mb-8 flex items-center gap-2">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-diary-accent text-diary-primary">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
                    </svg>
                </div>
                <span class="text-2xl font-semibold text-diary-primary">一行日記</span>
            </div>

            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-sm sm:p-8">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
