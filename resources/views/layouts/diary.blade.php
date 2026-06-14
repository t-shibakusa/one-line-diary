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
    <body
        class="font-sans antialiased bg-diary-bg text-diary-text"
        x-data="{ sidebarOpen: false }"
        x-bind:class="{ 'overflow-hidden lg:overflow-auto': sidebarOpen }"
        @keydown.escape.window="sidebarOpen = false"
    >
        <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
            <div class="absolute -right-20 top-10 h-64 w-64 rounded-full bg-diary-accent/40 blur-3xl"></div>
            <div class="absolute bottom-20 left-1/3 h-48 w-48 rounded-full bg-diary-accent/30 blur-3xl"></div>
        </div>

        @include('layouts.partials.diary-sidebar')

        <div
            x-show="sidebarOpen"
            x-cloak
            x-transition:enter="transition-opacity ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-20 bg-black/40 lg:hidden"
            aria-hidden="true"
        ></div>

        <div class="relative min-h-screen lg:pl-52">
            <header class="flex items-center gap-3 px-4 py-4 sm:gap-4 sm:px-6 sm:py-6 lg:px-10">
                <button
                    type="button"
                    @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-diary-border bg-diary-surface text-diary-primary transition hover:bg-diary-accent lg:hidden"
                    aria-label="メニューを開く"
                    :aria-expanded="sidebarOpen"
                >
                    <svg x-show="!sidebarOpen" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="sidebarOpen" x-cloak class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <div class="min-w-0 flex-1">
                    <h1 class="truncate text-lg font-bold text-diary-primary sm:text-2xl">
                        こんにちは、{{ Auth::user()->name }} さん
                    </h1>
                    <p class="mt-0.5 hidden text-sm text-diary-muted sm:block">今日も一行、心を整える時間を。</p>
                </div>

                <div class="hidden shrink-0 items-center gap-3 sm:flex">
                    <div class="flex max-w-[12rem] items-center gap-3 rounded-full border border-diary-border bg-diary-surface px-4 py-2 shadow-diary">
                        @include('layouts.partials.user-avatar', ['user' => Auth::user(), 'size' => 'sm'])
                        <span class="truncate font-medium text-diary-primary">{{ Auth::user()->name }}</span>
                    </div>
                </div>

                <div class="shrink-0 sm:hidden">
                    @include('layouts.partials.user-avatar', ['user' => Auth::user(), 'size' => 'sm'])
                </div>
            </header>

            <main class="px-4 pb-10 pb-[max(2.5rem,env(safe-area-inset-bottom))] sm:px-6 lg:px-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
