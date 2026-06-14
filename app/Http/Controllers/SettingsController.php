<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserAvatarRequest;
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

    public function updateAvatar(UpdateUserAvatarRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->deleteStoredAvatar();
        $avatarPath = $user->storeUploadedAvatar($request->file('avatar'));

        $user->update(['avatar_path' => $avatarPath]);

        return redirect()
            ->route('settings.index')
            ->with('status', 'プロフィール画像を更新しました。');
    }
}
