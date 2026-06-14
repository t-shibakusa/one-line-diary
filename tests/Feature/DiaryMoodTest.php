<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryMoodTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_diary_with_mood(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '今日は散歩して気分が良かった',
            'diary_date' => '2025-05-19',
            'mood' => 'great',
        ]);

        $response->assertRedirect(route('diaries.index'));

        $this->assertDatabaseHas('diaries', [
            'user_id' => $user->id,
            'body' => '今日は散歩して気分が良かった',
            'mood' => 'great',
        ]);
    }

    public function test_user_can_create_diary_without_mood(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '特に記録なし',
            'diary_date' => '2025-05-20',
        ]);

        $response->assertRedirect(route('diaries.index'));

        $this->assertDatabaseHas('diaries', [
            'user_id' => $user->id,
            'body' => '特に記録なし',
            'mood' => null,
        ]);
    }

    public function test_invalid_mood_is_rejected_on_create(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => 'テスト',
            'diary_date' => '2025-05-21',
            'mood' => 'invalid',
        ]);

        $response->assertSessionHasErrors('mood');
        $this->assertDatabaseCount('diaries', 0);
    }

    public function test_user_can_update_diary_mood(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'diary_date' => '2025-05-22',
            'mood' => 'normal',
        ]);

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => $diary->body,
            'diary_date' => '2025-05-22',
            'mood' => 'good',
        ]);

        $response->assertRedirect(route('diaries.index'));

        $this->assertDatabaseHas('diaries', [
            'id' => $diary->id,
            'mood' => 'good',
        ]);
    }

    public function test_user_can_clear_diary_mood_on_update(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'diary_date' => '2025-05-23',
            'mood' => 'tired',
        ]);

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => $diary->body,
            'diary_date' => '2025-05-23',
            'mood' => '',
        ]);

        $response->assertRedirect(route('diaries.index'));

        $this->assertDatabaseHas('diaries', [
            'id' => $diary->id,
            'mood' => null,
        ]);
    }

    public function test_index_displays_mood_emoji(): void
    {
        $user = User::factory()->create();
        Diary::factory()->for($user)->create([
            'body' => '今日は散歩して気分が良かった',
            'diary_date' => '2025-05-24',
            'mood' => 'great',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('😊', false);
        $response->assertSee('今日は散歩して気分が良かった', false);
    }
}
