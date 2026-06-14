<?php

use App\Models\Diary;
use App\Models\User;

it('stores a diary from the home quick write form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(route('diaries.store'), [
            'body' => 'Pestで書いた一行日記',
            'diary_date' => now()->addDay()->toDateString(),
            'mood' => 'good',
        ])
        ->assertRedirect(route('diaries.index'));

    $this->assertDatabaseHas('diaries', [
        'user_id' => $user->id,
        'body' => 'Pestで書いた一行日記',
        'mood' => 'good',
    ]);
});

it('shows recent diaries on home', function () {
    $user = User::factory()->create();
    Diary::factory()->for($user)->create([
        'body' => 'Pest一覧テスト',
        'diary_date' => '2025-05-19',
    ]);

    $this->actingAs($user)
        ->get(route('diaries.index'))
        ->assertOk()
        ->assertSee('Pest一覧テスト');
});

it('includes pagination anchor for recent diaries', function () {
    $user = User::factory()->create();

    for ($i = 0; $i < 6; $i++) {
        Diary::factory()->for($user)->create([
            'body' => "Pest日記{$i}",
            'diary_date' => now()->subDays($i)->format('Y-m-d'),
        ]);
    }

    $this->actingAs($user)
        ->get(route('diaries.index', ['page' => 2]))
        ->assertOk()
        ->assertSee('id="recent-diaries"', false)
        ->assertSee('#recent-diaries', false);
});
