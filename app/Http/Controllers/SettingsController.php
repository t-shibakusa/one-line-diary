<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function index(Request $request): View
    {
        return view('settings.index', [
            'theme' => $request->cookie('theme', 'light'),
        ]);
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => ['required', 'in:light,dark'],
        ]);

        return redirect()
            ->route('settings.index')
            ->with('status', '画面モードを変更しました。')
            ->cookie('theme', $validated['theme'], 60 * 24 * 365);
    }

    public function password(): View
    {
        return view('settings.password');
    }
}
