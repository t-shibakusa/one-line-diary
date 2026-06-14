<x-diary-layout>
    <section>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-diary-primary">日記詳細</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('diaries.index') }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold text-diary-muted transition hover:bg-diary-accent hover:text-diary-primary">
                    一覧へ戻る
                </a>
                <a href="{{ route('diaries.edit', $diary) }}"
                   class="rounded-xl bg-diary-primary px-4 py-2 text-sm font-semibold text-white transition hover:bg-diary-primary/90">
                    編集する
                </a>
            </div>
        </div>

        <article class="rounded-2xl bg-diary-surface p-6 shadow-sm lg:p-8">
            <div class="flex flex-wrap items-center gap-2">
                <time datetime="{{ $diary->diary_date->format('Y-m-d') }}" class="text-lg font-bold text-diary-primary">
                    {{ $diary->diary_date->locale('ja')->isoFormat('YYYY.MM.DD (ddd)') }}
                </time>
                @if ($diary->mood)
                    <span class="inline-flex items-center gap-1 rounded-full bg-diary-accent px-3 py-1 text-sm text-diary-primary">
                        <span aria-hidden="true">{{ $diary->moodEmoji() }}</span>
                        {{ $diary->moodLabel() }}
                    </span>
                @endif
            </div>

            @if ($diary->image_path)
                <div class="mt-4 overflow-hidden rounded-xl">
                    <img src="{{ route('diaries.image', $diary) }}" alt="" class="max-h-80 w-full object-cover">
                </div>
            @endif

            <p class="mt-4 whitespace-pre-wrap text-diary-text">{{ $diary->body }}</p>
        </article>
    </section>
</x-diary-layout>
