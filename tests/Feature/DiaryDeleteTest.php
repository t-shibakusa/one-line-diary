<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryDeleteTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_delete_own_diary(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'body' => '削除対象の日記',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->delete(route('diaries.destroy', $diary));

        $response->assertRedirect(route('diaries.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseMissing('diaries', [
            'id' => $diary->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_diary(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $diary = Diary::factory()->for($otherUser)->create([
            'body' => '他人の日記',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->delete(route('diaries.destroy', $diary));

        $response->assertForbidden();

        $this->assertDatabaseHas('diaries', [
            'id' => $diary->id,
        ]);
    }
}
