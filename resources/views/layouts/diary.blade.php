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
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -right-20 top-10 h-64 w-64 rounded-full bg-diary-accent/40 blur-3xl"></div>
            <div class="absolute bottom-20 left-1/3 h-48 w-48 rounded-full bg-diary-accent/30 blur-3xl"></div>
        </div>

        @include('layouts.partials.diary-sidebar')

        <div class="relative min-h-screen pl-52">
            <header class="flex items-center justify-between px-8 py-6 lg:px-10">
                <div>
                    <h1 class="text-2xl font-bold text-diary-primary">
                        こんにちは、{{ Auth::user()->name }} さん
                    </h1>
                    <p class="mt-1 text-sm text-diary-muted">今日も一行、心を整える時間を。</p>
                </div>

                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-3 rounded-full border border-diary-border bg-diary-surface px-4 py-2 shadow-diary">
                        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-diary-accent text-diary-primary">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <span class="font-medium text-diary-primary">{{ Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            <main class="px-8 pb-10 lg:px-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
