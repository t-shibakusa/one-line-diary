<div class="mb-6 rounded-2xl bg-diary-surface p-5 shadow-sm sm:p-6">
    <p class="mb-3 text-sm font-semibold text-diary-muted">今日の植物</p>

    <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-center">
        <div class="flex h-28 w-28 shrink-0 items-center justify-center rounded-2xl bg-diary-accent p-3">
            <img src="{{ $plant['image_url'] }}"
                 alt="{{ $plant['name'] }} Lv.{{ $plant['level'] }}"
                 class="h-full w-full object-contain">
        </div>

        <div class="text-center sm:text-left">
            <p class="text-xl font-bold text-diary-primary">
                {{ $plant['emoji'] }} {{ $plant['name'] }} Lv.{{ $plant['level'] }}
            </p>
            <p class="mt-1 text-sm text-diary-muted">日記投稿数：{{ $plant['diary_count'] }}件</p>
            @if ($plant['is_max_level'])
                <p class="mt-2 text-sm font-medium text-diary-primary">最大まで成長しました</p>
            @else
                <p class="mt-2 text-sm font-medium text-diary-primary">次の成長まであと{{ $plant['until_next_level'] }}件</p>
            @endif
        </div>
    </div>
</div>
