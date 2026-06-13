<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_diary(): void
    {
        $response = $this->post(route('diaries.store'), [
            'body' => 'テスト日記',
            'diary_date' => '2025-05-19',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_create_diary(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '今日は晴れだった',
            'diary_date' => '2025-05-19',
        ]);

        $response->assertRedirect(route('diaries.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('diaries', [
            'user_id' => $user->id,
            'body' => '今日は晴れだった',
        ]);

        $this->assertTrue(
            Diary::query()
                ->where('user_id', $user->id)
                ->whereDate('diary_date', '2025-05-19')
                ->exists()
        );
    }

    public function test_body_is_required(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '',
            'diary_date' => '2025-05-19',
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_body_cannot_exceed_140_characters(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => str_repeat('あ', 141),
            'diary_date' => '2025-05-19',
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_duplicate_diary_date_is_rejected(): void
    {
        $user = User::factory()->create();

        Diary::factory()->for($user)->create([
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '同日の2件目',
            'diary_date' => '2025-05-19',
        ]);

        $response->assertSessionHasErrors('diary_date');
        $this->assertDatabaseCount('diaries', 1);
    }
}
