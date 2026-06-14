<x-diary-layout>
    <section class="mx-auto max-w-3xl">
        <h1 class="mb-6 text-2xl font-bold text-diary-primary">新しく書く</h1>

        <div class="diary-card">
            <form method="POST" action="{{ route('diaries.store') }}" enctype="multipart/form-data" x-data="{ body: @js(old('body', '')) }">
                @csrf

                <div class="mb-4">
                    <label for="diary_date" class="mb-2 block text-sm font-medium text-diary-primary">日付</label>
                    <input
                        type="date"
                        id="diary_date"
                        name="diary_date"
                        value="{{ old('diary_date', now()->format('Y-m-d')) }}"
                        required
                        class="diary-input max-w-xs"
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
                            class="diary-textarea"
                        ></textarea>
                        <p class="absolute bottom-3 right-4 text-sm text-diary-muted">
                            <span x-text="body.length">0</span> / 140
                        </p>
                    </div>
                    @error('body')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                @include('diaries.partials.mood-field')

                <div class="mb-4">
                    @include('diaries.partials.image-field')
                </div>

                <div class="flex items-center justify-end pt-2">
                    <button type="submit" class="diary-btn-primary">
                        保存する
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-diary-layout>
