<aside
    class="fixed inset-y-0 left-0 z-30 flex w-52 flex-col border-r border-diary-border bg-diary-sidebar shadow-diary transition-transform duration-300 ease-in-out lg:translate-x-0"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
    aria-label="メインナビゲーション"
>
    <div class="flex min-h-screen flex-col px-4 py-6">
        <div class="mb-8 px-2">
            <div class="flex items-center gap-3">
                <div class="flex h-9 w-9 items-center justify-center rounded-full bg-diary-accent text-diary-primary">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-bold leading-tight text-diary-primary">Green Diary</p>
                    <p class="text-[11px] text-diary-muted">一行日記</p>
                </div>
            </div>
        </div>

        <nav class="space-y-1">
            @php
                $navItems = [
                    ['label' => 'ホーム', 'icon' => 'home', 'route' => 'diaries.index', 'active' => request()->routeIs('diaries.index', 'diaries.show', 'diaries.edit')],
                    ['label' => '新しく書く', 'icon' => 'write', 'route' => 'diaries.create', 'active' => request()->routeIs('diaries.create')],
                    ['label' => '設定', 'icon' => 'settings', 'route' => 'settings.index', 'active' => request()->routeIs('settings.*')],
                ];
            @endphp

            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   @click="sidebarOpen = false"
                   class="sidebar-link {{ $item['active'] ? 'sidebar-link--active' : '' }}">
                    @include('layouts.partials.diary-nav-icon', ['icon' => $item['icon']])
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="mt-auto pt-8">
            <div class="mb-4 flex justify-center px-2">
                <div class="flex h-24 w-full items-end justify-center rounded-2xl bg-diary-accent/50 px-4 pb-2">
                    <span class="text-4xl" aria-hidden="true">🪴</span>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" @click="sidebarOpen = false" class="sidebar-link w-full">
                    @include('layouts.partials.diary-nav-icon', ['icon' => 'logout'])
                    ログアウト
                </button>
            </form>
        </div>
    </div>
</aside>
