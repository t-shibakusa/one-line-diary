<x-diary-layout>
    <section>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-diary-primary">日記一覧</h1>
            <a href="{{ route('diaries.create') }}"
               class="rounded-xl bg-diary-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-diary-primary/90">
                新しく書く
            </a>
        </div>

        @include('diaries.partials.plant-card', ['plant' => $plant])

        @include('diaries.partials.calendar')

        @if (session('status'))
            <div class="mb-4 rounded-xl bg-diary-accent px-4 py-3 text-sm text-diary-primary">
                {{ session('status') }}
            </div>
        @endif

        @if ($diaries->isEmpty())
            <div class="rounded-2xl bg-diary-surface p-10 text-center shadow-sm">
                <p class="text-diary-muted">まだ日記がありません。</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($diaries as $diary)
                    <a href="{{ route('diaries.show', $diary) }}"
                       class="flex items-center gap-4 rounded-2xl bg-diary-surface p-4 shadow-sm transition hover:bg-diary-accent/30">
                        <div class="h-16 w-24 shrink-0 overflow-hidden rounded-xl bg-diary-accent">
                            @if ($diary->image_path)
                                <img src="{{ route('diaries.image', $diary) }}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center text-diary-muted">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <time datetime="{{ $diary->diary_date->format('Y-m-d') }}" class="text-sm font-semibold text-diary-primary">
                                {{ $diary->diary_date->locale('ja')->isoFormat('YYYY.MM.DD (ddd)') }}
                            </time>
                            <p class="mt-1 truncate text-diary-text">{{ $diary->body }}</p>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $diaries->links() }}
            </div>
        @endif
    </section>
</x-diary-layout>
