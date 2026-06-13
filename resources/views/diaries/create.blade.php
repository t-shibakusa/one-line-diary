<x-diary-layout>
    <section>
        <h1 class="mb-6 text-2xl font-bold text-diary-primary">新しく書く</h1>

        <div class="rounded-2xl bg-white p-6 shadow-sm lg:p-8">
            <form method="POST" action="{{ route('diaries.store') }}" x-data="{ body: @js(old('body', '')) }">
                @csrf

                <div class="mb-4">
                    <label for="diary_date" class="mb-2 block text-sm font-medium text-diary-primary">日付</label>
                    <input
                        type="date"
                        id="diary_date"
                        name="diary_date"
                        value="{{ old('diary_date', now()->format('Y-m-d')) }}"
                        required
                        class="w-full max-w-xs rounded-xl border border-gray-200 px-4 py-2 text-diary-text focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary"
                    >
                    @error('diary_date')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="body" class="mb-2 block text-sm font-medium text-diary-primary">今日の一行</label>
                    <div class="relative">
                        <textarea
                            id="body"
                            name="body"
                            rows="4"
                            maxlength="140"
                            required
                            x-model="body"
                            placeholder="今日のこと一行で書いてみましょう..."
                            class="w-full resize-none rounded-xl border border-gray-200 px-4 py-3 pb-8 text-diary-text placeholder:text-diary-muted/60 focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary"
                        ></textarea>
                        <p class="absolute bottom-3 right-4 text-sm text-diary-muted">
                            <span x-text="body.length">0</span> / 140
                        </p>
                    </div>
                    @error('body')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center gap-2 rounded-xl border border-dashed border-diary-muted/40 px-4 py-2 text-sm text-diary-muted/70">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
                        </svg>
                        画像を追加（任意）— Phase 9 で対応予定
                    </div>

                    <button
                        type="submit"
                        class="rounded-xl bg-diary-primary px-8 py-3 text-sm font-semibold text-white transition hover:bg-diary-primary/90"
                    >
                        保存する
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-diary-layout>
