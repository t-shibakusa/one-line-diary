@php
    $selectedMood = old('mood', '');
    if ($selectedMood === null) {
        $selectedMood = '';
    }

    $moodColors = [
        'great' => 'mood-circle--great',
        'good' => 'mood-circle--good',
        'normal' => 'mood-circle--normal',
        'tired' => 'mood-circle--tired',
        'bad' => 'mood-circle--bad',
    ];
@endphp

<div x-data="{ mood: @js($selectedMood) }">
    <p class="mb-2 text-xs font-medium text-diary-primary">気分を選択</p>
    <div class="flex flex-wrap items-center gap-2">
        @foreach (\App\Models\Diary::MOODS as $value => $info)
            <label class="cursor-pointer" title="{{ $info['label'] }}">
                <input
                    type="radio"
                    name="mood"
                    value="{{ $value }}"
                    class="mood-radio"
                    x-model="mood"
                >
                <span
                    class="mood-circle {{ $moodColors[$value] }}"
                    :data-selected="mood === '{{ $value }}' ? 'true' : 'false'"
                    aria-label="{{ $info['label'] }}"
                >{{ $info['emoji'] }}</span>
            </label>
        @endforeach
    </div>
    @error('mood')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
