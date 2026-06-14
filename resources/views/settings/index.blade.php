<x-diary-layout>
    <section class="mx-auto max-w-3xl">
        <h1 class="mb-6 text-2xl font-bold text-diary-primary">設定</h1>

        @if (session('status'))
            <div class="mb-4 rounded-xl border border-diary-border bg-diary-accent px-4 py-3 text-sm text-diary-primary">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-4">
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
