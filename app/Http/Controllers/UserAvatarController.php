<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserAvatarController extends Controller
{
    public function show(Request $request): StreamedResponse
    {
        $user = $request->user();

        if (! $user->avatar_path || ! Storage::disk('user_avatars')->exists($user->avatar_path)) {
            abort(404);
        }

        return Storage::disk('user_avatars')->response($user->avatar_path);
    }
}
