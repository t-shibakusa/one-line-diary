<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;
use App\Models\Diary;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DiaryController extends Controller
{
    public function index(): View
    {
        $diaries = auth()->user()
            ->diaries()
            ->orderByDesc('diary_date')
            ->paginate(5);

        return view('diaries.index', compact('diaries'));
    }

    public function create(): View
    {
        return view('diaries.create');
    }

    public function store(StoreDiaryRequest $request): RedirectResponse
    {
        $request->user()->diaries()->create($request->validated());

        return redirect()
            ->route('diaries.index')
            ->with('status', '日記を保存しました。');
    }

    public function edit(Diary $diary): View
    {
        $this->authorize('update', $diary);

        return view('diaries.edit', compact('diary'));
    }

    public function update(UpdateDiaryRequest $request, Diary $diary): RedirectResponse
    {
        $diary->update($request->validated());

        return redirect()
            ->route('diaries.index')
            ->with('status', '日記を更新しました。');
    }
}
