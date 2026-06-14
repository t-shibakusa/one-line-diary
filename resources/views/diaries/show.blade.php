<x-diary-layout>
    <section class="mx-auto max-w-3xl">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-diary-primary">日記詳細</h1>
            <div class="flex items-center gap-2">
                <a href="{{ route('diaries.index') }}" class="diary-btn-secondary">
                    ホームへ戻る
                </a>
                <a href="{{ route('diaries.edit', $diary) }}" class="diary-btn-primary">
                    編集する
                </a>
            </div>
        </div>

        <article class="diary-card">
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

            <p class="mt-4 whitespace-pre-wrap leading-relaxed text-diary-text">{{ $diary->body }}</p>

            @if ($diary->image_path)
                <div class="mt-4 rounded-xl bg-diary-accent-soft p-2">
                    <img src="{{ route('diaries.image', $diary) }}" alt="" class="mx-auto h-auto max-w-full rounded-lg">
                </div>
            @endif
        </article>
    </section>
</x-diary-layout>
