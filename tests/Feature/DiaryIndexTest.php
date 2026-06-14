<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('diaries.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_own_diaries(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'body' => '今日は晴れだった',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('今日は晴れだった');
        $response->assertSee('2025.05.19');
        $response->assertSee(route('diaries.show', $diary), false);
    }

    public function test_authenticated_user_cannot_see_other_users_diaries(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Diary::factory()->for($user)->create([
            'body' => '自分の日記',
            'diary_date' => '2025-05-19',
        ]);

        Diary::factory()->for($otherUser)->create([
            'body' => '他人の日記',
            'diary_date' => '2025-05-18',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('自分の日記');
        $response->assertDontSee('他人の日記');
    }

    public function test_diaries_are_displayed_in_descending_date_order(): void
    {
        $user = User::factory()->create();

        Diary::factory()->for($user)->create([
            'body' => '古い日記',
            'diary_date' => '2025-05-17',
        ]);

        Diary::factory()->for($user)->create([
            'body' => '新しい日記',
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSeeInOrder(['新しい日記', '古い日記']);
    }

    public function test_diaries_are_paginated_with_five_per_page(): void
    {
        $user = User::factory()->create();

        for ($i = 0; $i < 6; $i++) {
            Diary::factory()->for($user)->create([
                'body' => "日記{$i}",
                'diary_date' => now()->subDays($i)->format('Y-m-d'),
            ]);
        }

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('日記0');
        $response->assertSee('日記4');
        $response->assertDontSee('日記5');

        $response = $this->actingAs($user)->get(route('diaries.index', ['page' => 2]));

        $response->assertOk();
        $response->assertSee('日記5');
        $response->assertDontSee('日記0');
    }

    public function test_index_displays_diary_image_below_body(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create([
            'body' => '画像付きの日記',
            'diary_date' => '2025-05-19',
            'image_path' => 'sample.jpg',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('画像付きの日記');
        $response->assertSee(route('diaries.image', $diary), false);
    }
}
