<?php

namespace Tests\Unit;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiaryModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_diary_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create();

        $this->assertTrue($diary->user->is($user));
    }

    public function test_user_has_many_diaries(): void
    {
        $user = User::factory()->create();
        $diaries = Diary::factory()->count(3)->for($user)->create([
            'diary_date' => fn () => fake()->unique()->date(),
        ]);

        $this->assertCount(3, $user->diaries);
        $this->assertTrue($user->diaries->contains($diaries->first()));
    }

    public function test_mood_label_and_emoji(): void
    {
        $diary = Diary::factory()->create(['mood' => 'great']);

        $this->assertSame('とても良い', $diary->moodLabel());
        $this->assertSame('😊', $diary->moodEmoji());
    }

    public function test_mood_label_and_emoji_are_empty_when_unset(): void
    {
        $diary = Diary::factory()->create(['mood' => null]);

        $this->assertSame('', $diary->moodLabel());
        $this->assertSame('', $diary->moodEmoji());
    }
}
