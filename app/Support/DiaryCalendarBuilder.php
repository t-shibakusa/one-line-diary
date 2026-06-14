<?php

namespace App\Support;

use App\Models\Diary;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class DiaryCalendarBuilder
{
    /**
     * @return array{
     *     month: Carbon,
     *     weeks: list<list<array{
     *         date: Carbon,
     *         isCurrentMonth: bool,
     *         isToday: bool,
     *         diary: Diary|null
     *     }>>,
     *     prevMonth: string,
     *     nextMonth: string
     * }
     */
    public function build(User $user, ?string $month): array
    {
        $monthDate = $this->resolveMonth($month);

        /** @var Collection<string, Diary> $diariesByDate */
        $diariesByDate = $user->diaries()
            ->whereYear('diary_date', $monthDate->year)
            ->whereMonth('diary_date', $monthDate->month)
            ->get()
            ->keyBy(fn (Diary $diary): string => $diary->diary_date->format('Y-m-d'));

        $weeks = [];
        $cursor = $monthDate->copy()->startOfMonth()->startOfWeek(Carbon::SUNDAY);
        $end = $monthDate->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);
        $today = now()->startOfDay();

        while ($cursor->lte($end)) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $cursor->format('Y-m-d');

                $week[] = [
                    'date' => $cursor->copy(),
                    'isCurrentMonth' => $cursor->month === $monthDate->month,
                    'isToday' => $cursor->equalTo($today),
                    'diary' => $diariesByDate->get($dateKey),
                ];

                $cursor->addDay();
            }

            $weeks[] = $week;
        }

        return [
            'month' => $monthDate,
            'weeks' => $weeks,
            'prevMonth' => $monthDate->copy()->subMonth()->format('Y-m'),
            'nextMonth' => $monthDate->copy()->addMonth()->format('Y-m'),
        ];
    }

    private function resolveMonth(?string $month): Carbon
    {
        if ($month === null || $month === '') {
            return now()->startOfMonth();
        }

        if (! preg_match('/^\d{4}-\d{2}$/', $month)) {
            throw new InvalidArgumentException('Invalid month format.');
        }

        return Carbon::createFromFormat('Y-m-d', $month.'-01')->startOfMonth();
    }
}
