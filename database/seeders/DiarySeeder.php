<?php

namespace Database\Seeders;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DiarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Tatsuki',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $entries = [
            '散歩してリフレッシュできた ☀️',
            'おいしいコーヒーが飲めた ☕',
            '夜にゆっくり音楽を聴いた ♪',
            '新しい本を読み始めた 📖',
            'ランチでパスタを食べた 🍝',
            '早朝ジョギングで気持ちよかった 🏃',
            '友達と楽しくおしゃべりした 💬',
            '映画を見て感動した 🎬',
            '部屋の掃除をしてスッキリした ✨',
            '公園で写真を撮った 📷',
            '温かいスープで体がほっこりした 🍲',
        ];

        foreach ($entries as $index => $body) {
            Diary::query()->updateOrCreate(
                [
                    'user_id' => $user->id,
                    'diary_date' => now()->subDays($index)->toDateString(),
                ],
                [
                    'body' => $body,
                    'image_path' => null,
                ]
            );
        }
    }
}
