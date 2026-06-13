<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_own_diary(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'body' => '更新前の本文',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => '更新後の本文',
            'diary_date' => '2025-05-20',
        ]);

        $response->assertRedirect(route('diaries.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('diaries', [
            'id' => $diary->id,
            'user_id' => $user->id,
            'body' => '更新後の本文',
        ]);
    }

    public function test_user_cannot_update_other_users_diary(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $diary = Diary::factory()->for($otherUser)->create([
            'body' => '他人の日記',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => '書き換え試行',
            'diary_date' => '2025-05-19',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseHas('diaries', [
            'id' => $diary->id,
            'body' => '他人の日記',
        ]);
    }

    public function test_user_cannot_access_other_users_edit_page(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $diary = Diary::factory()->for($otherUser)->create();

        $response = $this->actingAs($user)->get(route('diaries.edit', $diary));

        $response->assertForbidden();
    }

    public function test_body_is_required_on_update(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => '',
            'diary_date' => '2025-05-19',
        ]);

        $response->assertSessionHasErrors('body');
    }

    public function test_body_cannot_exceed_140_characters_on_update(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => str_repeat('あ', 141),
            'diary_date' => '2025-05-19',
        ]);

        $response->assertSessionHasErrors('body');
    }
}
