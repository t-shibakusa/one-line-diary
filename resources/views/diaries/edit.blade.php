<x-diary-layout>
    <section class="mx-auto max-w-3xl">
        <h1 class="mb-6 text-xl font-bold text-diary-primary sm:text-2xl">日記を編集</h1>

        <div class="diary-card">
            <form method="POST" action="{{ route('diaries.update', $diary) }}" enctype="multipart/form-data" x-data="{ body: @js(old('body', $diary->body)) }">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="diary_date" class="mb-2 block text-sm font-medium text-diary-primary">日付</label>
                    <input
                        type="date"
                        id="diary_date"
                        name="diary_date"
                        value="{{ old('diary_date', $diary->diary_date->format('Y-m-d')) }}"
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

                @include('diaries.partials.mood-field', ['selected' => old('mood', $diary->mood)])

                <div class="mb-4">
                    @include('diaries.partials.image-field', ['diary' => $diary, 'currentImage' => $diary->image_path])
                </div>

                <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:items-center sm:justify-between">
                    <a href="{{ route('diaries.index') }}"
                       class="text-center text-sm font-medium text-diary-muted transition hover:text-diary-primary sm:text-left">
                        ホームに戻る
                    </a>

                    <button type="submit" class="diary-btn-primary w-full sm:w-auto">
                        更新する
                    </button>
                </div>
            </form>
        </div>

        <div class="mt-6 diary-card border-red-100">
            <h2 class="text-sm font-semibold text-red-600">日記を削除</h2>
            <p class="mt-2 text-sm text-diary-muted">削除すると元に戻せません。</p>
            <form method="POST" action="{{ route('diaries.destroy', $diary) }}" class="mt-4"
                  onsubmit="return confirm('この日記を削除しますか？');">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="rounded-xl border border-red-200 px-6 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-50">
                    削除する
                </button>
            </form>
        </div>
    </section>
</x-diary-layout>
