@php
    $selectedMood = old('mood', $selected ?? '');
    if ($selectedMood === null) {
        $selectedMood = '';
    }
@endphp

<div class="mb-4" x-data="{ mood: @js($selectedMood) }">
    <p class="mb-2 block text-sm font-medium text-diary-primary">今日の気分（任意）</p>
    <div class="flex flex-wrap gap-2">
        <label
            class="mood-option mood-option--unset"
            :data-selected="mood === '' ? 'true' : 'false'"
        >
            <input
                type="radio"
                name="mood"
                value=""
                class="mood-radio"
                x-model="mood"
            >
            未選択
        </label>
        @foreach (\App\Models\Diary::MOODS as $value => $info)
            <label
                class="mood-option"
                :data-selected="mood === '{{ $value }}' ? 'true' : 'false'"
            >
                <input
                    type="radio"
                    name="mood"
                    value="{{ $value }}"
                    class="mood-radio"
                    x-model="mood"
                >
                <span aria-hidden="true">{{ $info['emoji'] }}</span>
                {{ $info['label'] }}
            </label>
        @endforeach
    </div>
    @error('mood')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
