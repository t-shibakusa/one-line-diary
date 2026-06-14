<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-bold text-diary-primary">新規登録</h1>
        <p class="mt-2 text-sm text-diary-muted">アカウント情報を入力してください</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-4">
            <label for="name" class="mb-2 block text-sm font-medium text-diary-primary">名前</label>
            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="diary-input"
            >
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="mb-2 block text-sm font-medium text-diary-primary">メールアドレス</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="diary-input"
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
                autocomplete="new-password"
                class="diary-input"
            >
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="mb-2 block text-sm font-medium text-diary-primary">パスワード（確認）</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="diary-input"
            >
            @error('password_confirmation')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full diary-btn-primary py-3"
        >
            登録する
        </button>
    </form>

    <div class="mt-6 text-center text-sm">
        <p class="text-diary-muted">
            すでにアカウントをお持ちの方は
            <a href="{{ route('login') }}" class="font-semibold text-diary-primary transition hover:text-diary-primary/80">
                ログイン
            </a>
        </p>
    </div>
</x-guest-layout>
