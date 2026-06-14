<x-diary-layout>
    <section>
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-diary-border bg-diary-accent px-4 py-3 text-sm text-diary-primary">
                {{ session('status') }}
            </div>
        @endif

        <div class="home-dashboard">
            <div class="min-w-0 space-y-6">
                @include('diaries.partials.quick-write-form')

                <div id="recent-diaries" class="diary-card scroll-mt-6">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
                        <h2 class="text-lg font-bold text-diary-primary">最近の日記</h2>
                        <a href="{{ route('diaries.create') }}" class="text-sm font-medium text-diary-primary transition hover:text-diary-primary-dark">
                            新しく書く →
                        </a>
                    </div>

                    @if ($diaries->isEmpty())
                        <p class="py-8 text-center text-diary-muted">まだ日記がありません。</p>
                    @else
                        <ul class="divide-y divide-diary-border">
                            @foreach ($diaries as $diary)
                                <li class="flex items-start gap-3 py-4 first:pt-0 last:pb-0">
                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-diary-accent text-lg">
                                        @if ($diary->mood)
                                            <span aria-label="{{ $diary->moodLabel() }}">{{ $diary->moodEmoji() }}</span>
                                        @else
                                            <span class="text-diary-muted" aria-hidden="true">📝</span>
                                        @endif
                                    </div>

                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('diaries.show', $diary) }}" class="block text-diary-text transition hover:text-diary-primary">
                                            <p class="line-clamp-2">{{ $diary->body }}</p>
                                        </a>
                                        @if ($diary->image_path)
                                            <a href="{{ route('diaries.show', $diary) }}" class="mt-2 block">
                                                <img src="{{ route('diaries.image', $diary) }}"
                                                     alt=""
                                                     class="h-20 w-32 rounded-lg object-cover">
                                            </a>
                                        @endif
                                        <time datetime="{{ $diary->diary_date->format('Y-m-d') }}" class="mt-1 block text-xs text-diary-muted">
                                            {{ $diary->diary_date->locale('ja')->isoFormat('YYYY.MM.DD (ddd)') }}
                                        </time>
                                    </div>

                                    <div class="relative shrink-0" x-data="{ open: false }">
                                        <button type="button"
                                                @click="open = !open"
                                                class="rounded-lg p-2.5 text-diary-muted transition hover:bg-diary-accent hover:text-diary-primary"
                                                aria-label="メニュー">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                            </svg>
                                        </button>
                                        <div x-show="open"
                                             @click.outside="open = false"
                                             x-cloak
                                             class="absolute end-0 z-10 mt-1 w-36 rounded-xl border border-diary-border bg-diary-surface py-1 shadow-diary">
                                            <a href="{{ route('diaries.show', $diary) }}"
                                               class="block px-4 py-2 text-sm text-diary-text hover:bg-diary-accent">詳細</a>
                                            <a href="{{ route('diaries.edit', $diary) }}"
                                               class="block px-4 py-2 text-sm text-diary-text hover:bg-diary-accent">編集</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        @if ($diaries->hasPages())
                            <div class="mt-4 overflow-x-auto border-t border-diary-border pt-4">
                                {{ $diaries->fragment('recent-diaries')->links() }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <aside class="space-y-5">
                @include('diaries.partials.plant-widget', ['plant' => $plant])
                @include('diaries.partials.calendar-widget')
                @include('diaries.partials.mood-stats')

                <p class="px-2 text-center text-xs leading-relaxed text-diary-muted">
                    🌿 小さな一行が、未来の自分を育てていく。 🌿
                </p>
            </aside>
        </div>
    </section>

    @if (request()->has('page'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('recent-diaries')?.scrollIntoView({ block: 'start' });
            });
        </script>
    @endif
</x-diary-layout>
