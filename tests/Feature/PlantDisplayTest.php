<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantDisplayTest extends TestCase
{
    use RefreshDatabase;

    private function createDiariesForUser(User $user, int $count): void
    {
        $offset = $user->diaries()->count();

        for ($i = 0; $i < $count; $i++) {
            Diary::factory()->for($user)->create([
                'diary_date' => now()->subDays($offset + $i)->format('Y-m-d'),
            ]);
        }
    }

    public function test_diary_index_displays_plant_card(): void
    {
        $user = User::factory()->create();
        $this->createDiariesForUser($user, 4);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('今日の植物');
        $response->assertSee('Lv.2 芽');
        $response->assertSee('日記投稿数：4件');
        $response->assertSee('次の成長まであと3件');
        $response->assertSee('images/plants/plant_lv_05.png', false);
    }

    public function test_diary_index_shows_max_growth_message_at_level_five(): void
    {
        $user = User::factory()->create();
        $this->createDiariesForUser($user, 30);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertSee('Lv.5 木');
        $response->assertSee('最大まで成長しました');
        $response->assertDontSee('次の成長まであと');
    }
}
