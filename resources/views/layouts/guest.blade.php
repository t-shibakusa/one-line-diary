<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => request()->cookie('theme') === 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', '一行日記') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @include('layouts.partials.theme-styles')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-diary-bg text-diary-text">
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -right-16 top-16 h-56 w-56 rounded-full bg-diary-accent/40 blur-3xl"></div>
            <div class="absolute bottom-10 left-10 h-40 w-40 rounded-full bg-diary-accent/30 blur-3xl"></div>
        </div>

        <div class="relative flex min-h-screen flex-col items-center justify-center px-4 py-10">
            <div class="mb-8 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-diary-accent text-diary-primary">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-diary-primary">Green Diary</p>
                    <p class="text-sm text-diary-muted">一行日記</p>
                </div>
            </div>

            <div class="w-full max-w-md diary-card">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
