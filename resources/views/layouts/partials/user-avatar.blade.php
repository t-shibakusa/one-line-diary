@props(['user', 'size' => 'sm'])

@php
    $sizeClasses = [
        'sm' => 'h-9 w-9',
        'md' => 'h-20 w-20',
    ];
    $class = $sizeClasses[$size] ?? $sizeClasses['sm'];
@endphp

@if ($user->hasAvatar())
    <img src="{{ route('settings.avatar') }}" alt="" class="{{ $class }} rounded-full object-cover">
@else
    <div class="{{ $class }} flex items-center justify-center rounded-full bg-diary-accent text-diary-primary">
        <svg class="{{ $size === 'md' ? 'h-8 w-8' : 'h-5 w-5' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
    </div>
@endif
