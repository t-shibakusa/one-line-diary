<?php

namespace Tests\Unit;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlantGrowthTest extends TestCase
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

    public function test_plant_level_is_seed_for_zero_to_two_diaries(): void
    {
        $user = User::factory()->create();

        $this->assertSame(1, $user->plantLevel());
        $this->assertSame('種', $user->plantName());
        $this->assertSame('🌰', $user->plantEmoji());

        $this->createDiariesForUser($user, 2);

        $this->assertSame(1, $user->fresh()->plantLevel());
    }

    public function test_plant_level_changes_by_diary_count(): void
    {
        $user = User::factory()->create();

        $this->createDiariesForUser($user, 3);
        $this->assertSame(2, $user->fresh()->plantLevel());
        $this->assertSame('芽', $user->fresh()->plantName());

        $this->createDiariesForUser($user, 4);
        $this->assertSame(3, $user->fresh()->plantLevel());

        $this->createDiariesForUser($user, 7);
        $this->assertSame(4, $user->fresh()->plantLevel());

        $this->createDiariesForUser($user, 16);
        $this->assertSame(5, $user->fresh()->plantLevel());
        $this->assertSame('木', $user->fresh()->plantName());
    }

    public function test_plant_image_level_uses_twenty_stage_images(): void
    {
        $user = User::factory()->create();

        $this->assertSame(1, $user->plantImageLevel());
        $this->assertStringContainsString('plant_lv_01.png', $user->plantImageUrl());

        $this->createDiariesForUser($user, 9);
        $this->assertSame(10, $user->fresh()->plantImageLevel());

        $this->createDiariesForUser($user, 21);
        $this->assertSame(20, $user->fresh()->plantImageLevel());
        $this->assertStringContainsString('plant_lv_20.png', $user->fresh()->plantImageUrl());
    }

    public function test_diaries_until_next_plant_level(): void
    {
        $user = User::factory()->create();

        $this->assertSame(3, $user->diariesUntilNextPlantLevel());

        $this->createDiariesForUser($user, 9);
        $this->assertSame(5, $user->fresh()->diariesUntilNextPlantLevel());

        $this->createDiariesForUser($user, 21);
        $this->assertNull($user->fresh()->diariesUntilNextPlantLevel());
    }

    public function test_plant_level_is_recalculated_when_diary_is_deleted(): void
    {
        $user = User::factory()->create();
        $this->createDiariesForUser($user, 7);

        $this->assertSame(3, $user->fresh()->plantLevel());

        $user->diaries()->orderBy('diary_date')->first()->delete();

        $this->assertSame(2, $user->fresh()->plantLevel());
    }

    public function test_plant_status_returns_expected_structure(): void
    {
        $user = User::factory()->create();
        $this->createDiariesForUser($user, 4);

        $status = $user->fresh()->plantStatus();

        $this->assertSame(2, $status['level']);
        $this->assertSame(5, $status['image_level']);
        $this->assertSame('芽', $status['name']);
        $this->assertSame(4, $status['diary_count']);
        $this->assertSame(3, $status['until_next_level']);
        $this->assertFalse($status['is_max_level']);
    }
}
