<div class="diary-card">
    <div class="mb-3 flex items-center justify-between">
        <h2 class="text-sm font-semibold text-diary-primary">
            {{ $calendar['month']->locale('ja')->isoFormat('YYYY年M月') }}
        </h2>
        <div class="flex items-center gap-1">
            <a href="{{ route('diaries.index', ['month' => $calendar['prevMonth']]) }}"
               class="rounded-md px-3 py-2 text-xs font-medium text-diary-muted transition hover:bg-diary-accent hover:text-diary-primary"
               aria-label="前の月">
                ‹
            </a>
            <a href="{{ route('diaries.index') }}"
               class="rounded-md px-3 py-2 text-xs font-medium text-diary-muted transition hover:bg-diary-accent hover:text-diary-primary {{ $calendar['month']->format('Y-m') === now()->format('Y-m') ? 'pointer-events-none opacity-40' : '' }}">
                今月
            </a>
            <a href="{{ route('diaries.index', ['month' => $calendar['nextMonth']]) }}"
               class="rounded-md px-3 py-2 text-xs font-medium text-diary-muted transition hover:bg-diary-accent hover:text-diary-primary"
               aria-label="次の月">
                ›
            </a>
        </div>
    </div>

    <table class="w-full table-fixed border-collapse">
        <thead>
            <tr class="text-center text-[11px] font-semibold text-diary-muted">
                @foreach (['日', '月', '火', '水', '木', '金', '土'] as $weekday)
                    <th scope="col" class="pb-1 font-semibold">{{ $weekday }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($calendar['weeks'] as $week)
                <tr>
                    @foreach ($week as $day)
                        @php
                            $dayClasses = 'mx-auto flex h-9 w-9 items-center justify-center rounded-full text-xs transition sm:h-8 sm:w-8 sm:text-[11px] ';
                            if (! $day['isCurrentMonth']) {
                                $dayClasses .= 'text-diary-muted/40 ';
                            } elseif ($day['isToday']) {
                                $dayClasses .= 'font-bold text-diary-primary ';
                            } else {
                                $dayClasses .= 'text-diary-text ';
                            }
                            if ($day['diary']) {
                                $dayClasses .= 'bg-diary-primary font-semibold text-white hover:bg-diary-primary-dark ';
                            } elseif ($day['isCurrentMonth']) {
                                $dayClasses .= 'hover:bg-diary-accent ';
                            }
                        @endphp

                        <td class="p-0 text-center align-middle">
                            @if ($day['diary'])
                                <a href="{{ route('diaries.show', $day['diary']) }}"
                                   class="{{ $dayClasses }}"
                                   title="{{ $day['date']->locale('ja')->isoFormat('M月D日') }}の日記">
                                    {{ $day['date']->day }}
                                </a>
                            @else
                                <div class="{{ $dayClasses }}">
                                    {{ $day['date']->day }}
                                </div>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
