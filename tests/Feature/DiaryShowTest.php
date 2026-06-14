<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_diary_detail(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'body' => '詳細表示テスト',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.show', $diary));

        $response->assertOk();
        $response->assertSee('詳細表示テスト');
        $response->assertSee('日記詳細');
    }

    public function test_user_cannot_view_other_users_diary_detail(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $diary = Diary::factory()->for($otherUser)->create([
            'body' => '他人の日記',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.show', $diary));

        $response->assertForbidden();
    }

    public function test_guest_cannot_view_diary_detail(): void
    {
        $diary = Diary::factory()->create();

        $response = $this->get(route('diaries.show', $diary));

        $response->assertRedirect(route('login'));
    }
}
