<div class="quick-write-card">
    <div class="mb-3 flex items-center gap-1.5">
        <span class="text-sm text-diary-primary" aria-hidden="true">🌿</span>
        <h2 class="text-base font-bold text-diary-primary">今日の一行を書く</h2>
    </div>

    <form
        method="POST"
        action="{{ route('diaries.store') }}"
        enctype="multipart/form-data"
        x-data="{ body: @js(old('body', '')) }"
    >
        @csrf
        <input type="hidden" name="diary_date" value="{{ old('diary_date', now()->format('Y-m-d')) }}">

        <div class="relative mb-3">
            <textarea
                id="quick_body"
                name="body"
                rows="3"
                maxlength="140"
                required
                x-model="body"
                placeholder="今日のことを一行で書いてみましょう..."
                class="quick-write-textarea"
            ></textarea>
            <p class="absolute bottom-2 right-3 text-xs text-diary-muted">
                <span x-text="body.length">0</span> / 140
            </p>
        </div>
        @error('body')
            <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
        @enderror
        @error('diary_date')
            <p class="mb-2 text-xs text-red-600">{{ $message }}</p>
        @enderror

        <div class="mb-3">
            @include('diaries.partials.mood-field-compact')
        </div>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="w-full sm:max-w-sm">
                @include('diaries.partials.image-field-compact')
            </div>

            <button type="submit" class="quick-write-btn w-full shrink-0 sm:w-auto sm:self-end">
                <span aria-hidden="true">🌿</span>
                保存する
            </button>
        </div>
    </form>
</div>
