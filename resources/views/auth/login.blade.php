<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-diary-primary">ログイン</h1>
        <p class="mt-2 text-sm text-diary-muted">メールアドレスとパスワードを入力してください</p>
    </div>

    @if (session('status'))
        <div class="mb-4 rounded-xl bg-diary-accent px-4 py-3 text-sm text-diary-primary">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label for="email" class="mb-2 block text-sm font-medium text-diary-primary">メールアドレス</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                class="w-full rounded-xl border border-gray-200 px-4 py-2 text-diary-text focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary"
            >
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="mb-2 block text-sm font-medium text-diary-primary">パスワード</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="current-password"
                class="w-full rounded-xl border border-gray-200 px-4 py-2 text-diary-text focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6 flex items-center">
            <input
                id="remember_me"
                type="checkbox"
                name="remember"
                class="rounded border-gray-300 text-diary-primary focus:ring-diary-primary"
            >
            <label for="remember_me" class="ms-2 text-sm text-diary-muted">ログイン状態を保持する</label>
        </div>

        <button
            type="submit"
            class="w-full rounded-xl bg-diary-primary px-8 py-3 text-sm font-semibold text-white transition hover:bg-diary-primary/90"
        >
            ログイン
        </button>
    </form>

    <div class="mt-6 space-y-3 text-center text-sm">
        @if (Route::has('password.request'))
            <p>
                <a href="{{ route('password.request') }}" class="text-diary-muted transition hover:text-diary-primary">
                    パスワードをお忘れですか？
                </a>
            </p>
        @endif

        @if (Route::has('register'))
            <p class="text-diary-muted">
                アカウントをお持ちでない方は
                <a href="{{ route('register') }}" class="font-semibold text-diary-primary transition hover:text-diary-primary/80">
                    新規登録
                </a>
            </p>
        @endif
    </div>
</x-guest-layout>
