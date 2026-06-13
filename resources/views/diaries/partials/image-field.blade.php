<div>
    <label for="image" class="mb-2 block text-sm font-medium text-diary-primary">画像（任意）</label>
    @if ($currentImage ?? false)
        <div class="mb-3">
            <img src="{{ route('diaries.image', $diary) }}" alt="" class="h-24 w-36 rounded-xl object-cover">
            <p class="mt-1 text-xs text-diary-muted">新しい画像を選ぶと差し替えられます</p>
        </div>
    @endif
    <label class="flex cursor-pointer items-center gap-2 rounded-xl border border-dashed border-diary-muted/40 px-4 py-3 text-sm text-diary-muted transition hover:border-diary-primary hover:text-diary-primary">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 3c2 2 4 6 4 9a4 4 0 11-8 0c0-3 2-7 4-9z" />
        </svg>
        <span>画像を追加（jpg / png / webp・最大2MB）</span>
        <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="sr-only">
    </label>
    @error('image')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
