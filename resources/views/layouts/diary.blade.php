<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => request()->cookie('theme') === 'dark'])>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', '一行日記') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @include('layouts.partials.theme-styles')

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-diary-bg text-diary-text">
        @include('layouts.partials.diary-sidebar')

        <div class="min-h-screen pl-64">
            <header class="flex items-center justify-end px-6 py-4 lg:px-10">
                <div class="flex items-center gap-3 rounded-full bg-diary-surface px-4 py-2 shadow-sm">
                    <div class="flex h-9 w-9 items-center justify-center rounded-full bg-diary-accent text-diary-primary">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
                        </svg>
                    </div>
                    <span class="font-medium text-diary-primary">{{ Auth::user()->name }}</span>
                </div>
            </header>

            <main class="px-6 pb-10 lg:px-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
