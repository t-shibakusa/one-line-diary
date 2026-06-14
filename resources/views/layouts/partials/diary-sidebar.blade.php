<aside class="fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-white shadow-sm rounded-r-2xl">
    <div class="flex min-h-screen flex-col px-5 py-6">
        <div class="mb-8 flex items-center gap-2 px-2">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-diary-accent text-diary-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
                </svg>
            </div>
            <span class="text-lg font-semibold text-diary-primary">一行日記</span>
        </div>

        <nav class="space-y-1">
            @php
                $navItems = [
                    ['label' => '日記一覧', 'icon' => 'list', 'route' => 'diaries.index', 'active' => request()->routeIs('diaries.index', 'diaries.edit', 'diaries.show')],
                    ['label' => '新しく書く', 'icon' => 'write', 'route' => 'diaries.create', 'active' => request()->routeIs('diaries.create')],
                    ['label' => '設定', 'icon' => 'settings', 'route' => null, 'active' => false],
                ];
            @endphp

            @foreach ($navItems as $item)
                @if ($item['route'])
                    <a href="{{ route($item['route']) }}"
                       class="{{ $item['active'] ? 'bg-diary-accent text-diary-primary' : 'text-diary-muted hover:bg-diary-accent/50 hover:text-diary-primary' }} flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition">
                        @include('layouts.partials.diary-nav-icon', ['icon' => $item['icon']])
                        {{ $item['label'] }}
                    </a>
                @else
                    <span class="flex cursor-default items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-diary-muted/70">
                        @include('layouts.partials.diary-nav-icon', ['icon' => $item['icon']])
                        {{ $item['label'] }}
                    </span>
                @endif
            @endforeach
        </nav>

        <div class="mt-auto pt-8">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-diary-muted transition hover:bg-diary-accent/50 hover:text-diary-primary">
                    @include('layouts.partials.diary-nav-icon', ['icon' => 'logout'])
                    ログアウト
                </button>
            </form>
        </div>
    </div>
</aside>
