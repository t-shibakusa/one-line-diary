<?php

use App\Models\User;

it('calculates plant level from diary count', function () {
    $user = User::factory()->create();

    expect($user->plantLevel())->toBe(1);

    for ($i = 0; $i < 3; $i++) {
        $user->diaries()->create([
            'body' => "日記{$i}",
            'diary_date' => now()->subDays($i)->toDateString(),
        ]);
    }

    expect($user->fresh()->plantLevel())->toBe(2);
});

it('returns mood label and emoji', function () {
    $user = User::factory()->create();
    $diary = $user->diaries()->create([
        'body' => 'テスト',
        'diary_date' => '2025-05-19',
        'mood' => 'great',
    ]);

    expect($diary->moodLabel())->toBe('とても良い')
        ->and($diary->moodEmoji())->toBe('😊');
});
