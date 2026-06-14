<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;
use App\Models\Diary;
use App\Support\DiaryCalendarBuilder;
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

        return view('diaries.index', compact('diaries', 'calendar', 'plant'));
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
