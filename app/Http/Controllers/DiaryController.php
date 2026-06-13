<?php

namespace App\Http\Controllers;

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
}
