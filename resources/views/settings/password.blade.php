<x-diary-layout>
    <section>
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold text-diary-primary">パスワード変更</h1>
            <a href="{{ route('settings.index') }}"
               class="rounded-xl px-4 py-2 text-sm font-semibold text-diary-muted transition hover:bg-diary-accent hover:text-diary-primary">
                設定へ戻る
            </a>
        </div>

        <div class="rounded-2xl bg-diary-surface p-6 shadow-sm lg:p-8">
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
                           class="w-full max-w-md rounded-xl border border-diary-border bg-diary-surface px-4 py-2 text-diary-text focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary">
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
                           class="w-full max-w-md rounded-xl border border-diary-border bg-diary-surface px-4 py-2 text-diary-text focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary">
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
                           class="w-full max-w-md rounded-xl border border-diary-border bg-diary-surface px-4 py-2 text-diary-text focus:border-diary-primary focus:outline-none focus:ring-1 focus:ring-diary-primary">
                    @if ($errors->updatePassword->has('password_confirmation'))
                        <p class="mt-2 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                    @endif
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <button type="submit"
                            class="rounded-xl bg-diary-primary px-8 py-3 text-sm font-semibold text-white transition hover:bg-diary-primary/90">
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
