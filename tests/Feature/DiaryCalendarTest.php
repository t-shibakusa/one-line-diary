<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryCalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_displays_calendar(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('日');
        $response->assertSee('土');
    }

    public function test_calendar_highlights_days_with_diaries_as_links(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'body' => 'カレンダー表示テスト',
            'diary_date' => now()->startOfMonth()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index', [
            'month' => now()->format('Y-m'),
        ]));

        $response->assertOk();
        $response->assertSee(route('diaries.show', $diary), false);
    }

    public function test_user_can_navigate_to_previous_month(): void
    {
        $user = User::factory()->create();
        $targetMonth = now()->subMonth()->format('Y-m');

        Diary::factory()->for($user)->create([
            'body' => '先月の日記',
            'diary_date' => now()->subMonth()->startOfMonth()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index', [
            'month' => $targetMonth,
        ]));

        $response->assertOk();
        $response->assertSee(now()->subMonth()->locale('ja')->isoFormat('YYYY年M月'));
        $response->assertSee('先月の日記');
    }

    public function test_user_can_navigate_to_next_month(): void
    {
        $user = User::factory()->create();
        $targetMonth = now()->addMonth()->format('Y-m');

        $response = $this->actingAs($user)->get(route('diaries.index', [
            'month' => $targetMonth,
        ]));

        $response->assertOk();
        $response->assertSee(now()->addMonth()->locale('ja')->isoFormat('YYYY年M月'));
    }

    public function test_invalid_month_parameter_falls_back_to_current_month(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('diaries.index', [
            'month' => 'invalid',
        ]));

        $response->assertOk();
        $response->assertSee(now()->locale('ja')->isoFormat('YYYY年M月'));
    }
}
