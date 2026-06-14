<x-diary-layout>
    <section class="mx-auto max-w-3xl">
        <h1 class="mb-6 text-2xl font-bold text-diary-primary">設定</h1>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-diary-border bg-diary-accent px-4 py-3 text-sm text-diary-primary">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-4">
            <div class="diary-card">
                <h2 class="text-lg font-semibold text-diary-primary">プロフィール画像</h2>
                <p class="mt-1 text-sm text-diary-muted">ヘッダーに表示するサムネイル画像を変更できます。</p>

                <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-center">
                    @include('layouts.partials.user-avatar', ['user' => Auth::user(), 'size' => 'md'])

                    <form method="POST" action="{{ route('settings.avatar.update') }}" enctype="multipart/form-data" class="flex-1">
                        @csrf
                        @method('PUT')

                        <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-dashed border-diary-border px-4 py-3 text-sm text-diary-muted transition hover:border-diary-primary hover:text-diary-primary">
                            <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span>画像を選択（jpg / jpeg / png / webp・最大2MB）</span>
                            <input type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only" onchange="this.form.submit()">
                        </label>
                        @error('avatar')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                </div>
            </div>

            <a href="{{ route('settings.password') }}"
               class="block diary-card transition hover:border-diary-primary/30">
                <h2 class="text-lg font-semibold text-diary-primary">パスワード変更</h2>
                <p class="mt-1 text-sm text-diary-muted">ログインに使用するパスワードを変更します。</p>
            </a>

            <div class="diary-card">
                <h2 class="text-lg font-semibold text-diary-primary">画面モード</h2>
                <p class="mt-1 text-sm text-diary-muted">アプリの表示モードを切り替えます。</p>

                <form method="POST" action="{{ route('settings.theme') }}" class="mt-4 flex gap-3">
                    @csrf
                    @method('PUT')

                    <button type="submit"
                            name="theme"
                            value="light"
                            class="{{ $theme === 'light' ? 'bg-diary-primary text-white' : 'border border-diary-border bg-diary-accent-soft text-diary-muted hover:text-diary-primary' }} rounded-xl px-6 py-2.5 text-sm font-semibold transition">
                        ライト
                    </button>
                    <button type="submit"
                            name="theme"
                            value="dark"
                            class="{{ $theme === 'dark' ? 'bg-diary-primary text-white' : 'border border-diary-border bg-diary-accent-soft text-diary-muted hover:text-diary-primary' }} rounded-xl px-6 py-2.5 text-sm font-semibold transition">
                        ダーク
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-diary-layout>
