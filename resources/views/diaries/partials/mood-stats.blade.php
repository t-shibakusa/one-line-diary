<div class="diary-card">
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-diary-primary">今月の気分記録</h2>
        <span class="text-xs text-diary-muted">{{ $calendar['month']->locale('ja')->isoFormat('YYYY年M月') }}</span>
    </div>

    <ul class="space-y-2">
        @foreach ($moodStats as $stat)
            <li class="flex items-center justify-between rounded-xl bg-diary-accent-soft/80 px-3 py-2 text-sm">
                <span class="flex items-center gap-2 text-diary-text">
                    <span aria-hidden="true">{{ $stat['emoji'] }}</span>
                    {{ $stat['label'] }}
                </span>
                <span class="font-semibold text-diary-primary">{{ $stat['count'] }} 回</span>
            </li>
        @endforeach
    </ul>
</div>
