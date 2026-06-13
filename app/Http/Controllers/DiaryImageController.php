<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DiaryImageController extends Controller
{
    public function show(Diary $diary): StreamedResponse
    {
        $this->authorize('view', $diary);

        if (! $diary->image_path || ! Storage::disk('diary_images')->exists($diary->image_path)) {
            abort(404);
        }

        return Storage::disk('diary_images')->response($diary->image_path);
    }
}
