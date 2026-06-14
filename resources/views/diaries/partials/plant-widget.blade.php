<div class="diary-card">
    <div class="mb-4 flex items-center justify-between">
        <p class="text-sm font-semibold text-diary-muted">今日の植物</p>
        @if (! $plant['is_max_level'])
            <span class="rounded-full bg-diary-accent px-2.5 py-0.5 text-xs font-semibold text-diary-primary">育成中</span>
        @endif
    </div>

    <div class="flex flex-col items-center text-center">
        <div class="mb-3 flex h-28 w-28 items-center justify-center rounded-2xl bg-diary-accent/60 p-3">
            <img src="{{ $plant['image_url'] }}"
                 alt="{{ $plant['name'] }} Lv.{{ $plant['level'] }}"
                 class="h-full w-full object-contain">
        </div>

        <p class="text-lg font-bold text-diary-primary">
            Lv.{{ $plant['level'] }} {{ $plant['name'] }}
        </p>
        <p class="mt-1 text-xs text-diary-muted">日記投稿数：{{ $plant['diary_count'] }}件</p>

        @if ($plant['is_max_level'])
            <p class="mt-3 text-sm font-medium text-diary-primary">最大まで成長しました</p>
        @else
            <div class="mt-4 w-full">
                <div class="mb-1 flex items-center justify-between text-xs text-diary-muted">
                    <span>{{ $plant['progress_current'] }} / {{ $plant['progress_target'] }} 件</span>
                </div>
                <div class="h-2 overflow-hidden rounded-full bg-diary-accent/60">
                    <div class="h-full rounded-full bg-diary-primary transition-all"
                         style="width: {{ $plant['progress_percent'] }}%"></div>
                </div>
                <p class="mt-2 text-sm font-medium text-diary-primary">次の成長まであと{{ $plant['until_next_level'] }}件</p>
            </div>
        @endif
    </div>
</div>
