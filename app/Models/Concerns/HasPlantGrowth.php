<?php

namespace App\Models\Concerns;

trait HasPlantGrowth
{
    /**
     * @return array<int, int> image level => minimum diary count
     */
    private function plantImageThresholds(): array
    {
        return [
            1 => 0,
            2 => 1,
            3 => 2,
            4 => 3,
            5 => 4,
            6 => 5,
            7 => 6,
            8 => 7,
            9 => 8,
            10 => 9,
            11 => 10,
            12 => 11,
            13 => 12,
            14 => 13,
            15 => 14,
            16 => 15,
            17 => 18,
            18 => 21,
            19 => 24,
            20 => 30,
        ];
    }

    public function plantLevel(): int
    {
        $count = $this->diaries()->count();

        return match (true) {
            $count >= 30 => 5,
            $count >= 14 => 4,
            $count >= 7 => 3,
            $count >= 3 => 2,
            default => 1,
        };
    }

    public function plantImageLevel(): int
    {
        $count = $this->diaries()->count();
        $level = 1;

        foreach ($this->plantImageThresholds() as $imageLevel => $minimumCount) {
            if ($count >= $minimumCount) {
                $level = $imageLevel;
            }
        }

        return $level;
    }

    public function plantName(): string
    {
        return match ($this->plantLevel()) {
            1 => '種',
            2 => '芽',
            3 => '若葉',
            4 => '鉢植え',
            5 => '木',
            default => '種',
        };
    }

    public function plantEmoji(): string
    {
        return match ($this->plantLevel()) {
            1 => '🌰',
            2 => '🌱',
            3 => '🌿',
            4 => '🪴',
            5 => '🌳',
            default => '🌰',
        };
    }

    public function nextPlantLevelCount(): ?int
    {
        return match ($this->plantLevel()) {
            1 => 3,
            2 => 7,
            3 => 14,
            4 => 30,
            default => null,
        };
    }

    public function diariesUntilNextPlantLevel(): ?int
    {
        $nextCount = $this->nextPlantLevelCount();

        if ($nextCount === null) {
            return null;
        }

        return max(0, $nextCount - $this->diaries()->count());
    }

    public function plantImageUrl(): string
    {
        $level = str_pad((string) $this->plantImageLevel(), 2, '0', STR_PAD_LEFT);

        return asset("images/plants/plant_lv_{$level}.png");
    }

    /**
     * @return array{
     *     level: int,
     *     image_level: int,
     *     name: string,
     *     emoji: string,
     *     image_url: string,
     *     diary_count: int,
     *     until_next_level: int|null,
     *     is_max_level: bool
     * }
     */
    public function plantStatus(): array
    {
        return [
            'level' => $this->plantLevel(),
            'image_level' => $this->plantImageLevel(),
            'name' => $this->plantName(),
            'emoji' => $this->plantEmoji(),
            'image_url' => $this->plantImageUrl(),
            'diary_count' => $this->diaries()->count(),
            'until_next_level' => $this->diariesUntilNextPlantLevel(),
            'is_max_level' => $this->plantLevel() === 5,
        ];
    }
}
