<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;
use App\Models\Diary;
use App\Models\User;
use App\Support\DiaryCalendarBuilder;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiaryController extends Controller
{
    public function index(Request $request, DiaryCalendarBuilder $calendarBuilder): View
    {
        $month = $request->query('month');

        try {
            $calendar = $calendarBuilder->build($request->user(), is_string($month) ? $month : null);
        } catch (\InvalidArgumentException) {
            $calendar = $calendarBuilder->build($request->user(), null);
        }

        $diaries = $request->user()
            ->diaries()
            ->orderByDesc('diary_date')
            ->paginate(5)
            ->withQueryString();

        $plant = $request->user()->plantStatus();

        $moodStats = $this->monthlyMoodStats($request->user(), $calendar['month']);

        return view('diaries.index', compact('diaries', 'calendar', 'plant', 'moodStats'));
    }

    /**
     * @return list<array{value: string, label: string, emoji: string, count: int}>
     */
    private function monthlyMoodStats(User $user, Carbon $month): array
    {
        $counts = $user->diaries()
            ->whereBetween('diary_date', [
                $month->copy()->startOfMonth()->format('Y-m-d'),
                $month->copy()->endOfMonth()->format('Y-m-d'),
            ])
            ->whereNotNull('mood')
            ->selectRaw('mood, count(*) as total')
            ->groupBy('mood')
            ->pluck('total', 'mood');

        $stats = [];

        foreach (Diary::MOODS as $value => $info) {
            $stats[] = [
                'value' => $value,
                'label' => $info['label'],
                'emoji' => $info['emoji'],
                'count' => (int) ($counts[$value] ?? 0),
            ];
        }

        return $stats;
    }

    public function create(): View
    {
        return view('diaries.create');
    }

    public function store(StoreDiaryRequest $request): RedirectResponse
    {
        $data = $request->safe()->only(['body', 'diary_date', 'mood']);

        if ($request->hasFile('image')) {
            $diary = new Diary;
            $data['image_path'] = $diary->storeUploadedImage($request->file('image'));
        }

        $request->user()->diaries()->create($data);

        return redirect()
            ->route('diaries.index')
            ->with('status', '日記を保存しました。');
    }

    public function show(Diary $diary): View
    {
        $this->authorize('view', $diary);

        return view('diaries.show', compact('diary'));
    }

    public function edit(Diary $diary): View
    {
        $this->authorize('update', $diary);

        return view('diaries.edit', compact('diary'));
    }

    public function update(UpdateDiaryRequest $request, Diary $diary): RedirectResponse
    {
        $data = $request->safe()->only(['body', 'diary_date', 'mood']);

        if ($request->hasFile('image')) {
            $diary->deleteStoredImage();
            $data['image_path'] = $diary->storeUploadedImage($request->file('image'));
        }

        $diary->update($data);

        return redirect()
            ->route('diaries.index')
            ->with('status', '日記を更新しました。');
    }

    public function destroy(Diary $diary): RedirectResponse
    {
        $this->authorize('delete', $diary);

        $diary->delete();

        return redirect()
            ->route('diaries.index')
            ->with('status', '日記を削除しました。');
    }
}
