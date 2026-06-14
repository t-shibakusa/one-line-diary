<div>
    <label for="quick_image" class="mb-1 block text-xs font-medium text-diary-primary">画像（任意）</label>
    <label class="quick-write-image-label">
        <svg class="h-3.5 w-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span>画像を追加（jpg / jpeg・最大2MB）</span>
        <input type="file" id="quick_image" name="image" accept=".jpg,.jpeg,image/jpeg" class="sr-only">
    </label>
    @error('image')
        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
    @enderror
</div>
