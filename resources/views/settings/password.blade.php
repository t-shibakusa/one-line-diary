<x-diary-layout>
    <section class="mx-auto max-w-3xl">
        <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-bold text-diary-primary sm:text-2xl">パスワード変更</h1>
            <a href="{{ route('settings.index') }}" class="diary-btn-secondary w-full justify-center sm:w-auto">
                設定へ戻る
            </a>
        </div>

        <div class="diary-card">
            <p class="mb-6 text-sm text-diary-muted">安全のため、十分に長いパスワードを設定してください。</p>

            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="mb-2 block text-sm font-medium text-diary-primary">現在のパスワード</label>
                    <input id="current_password"
                           type="password"
                           name="current_password"
                           required
                           autocomplete="current-password"
                           class="diary-input max-w-md">
                    @if ($errors->updatePassword->has('current_password'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-diary-primary">新しいパスワード</label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           class="diary-input max-w-md">
                    @if ($errors->updatePassword->has('password'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-medium text-diary-primary">新しいパスワード（確認）</label>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           class="diary-input max-w-md">
                    @if ($errors->updatePassword->has('password_confirmation'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit" class="diary-btn-primary">
                        保存する
                    </button>

                    @if (session('status') === 'password-updated')
                        <p class="text-sm text-diary-primary">パスワードを更新しました。</p>
                    @endif
                </div>
            </form>
        </div>
    </section>
</x-diary-layout>
