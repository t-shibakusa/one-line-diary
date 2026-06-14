<?php

namespace App\Models;

use Database\Factories\DiaryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Diary extends Model
{
    /** @use HasFactory<DiaryFactory> */
    use HasFactory;

    /**
     * @var array<string, array{label: string, emoji: string}>
     */
    public const MOODS = [
        'great' => ['label' => 'とても良い', 'emoji' => '😊'],
        'good' => ['label' => '良い', 'emoji' => '🙂'],
        'normal' => ['label' => '普通', 'emoji' => '😐'],
        'tired' => ['label' => '疲れた', 'emoji' => '😴'],
        'bad' => ['label' => 'つらい', 'emoji' => '😔'],
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'body',
        'mood',
        'image_path',
        'diary_date',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'diary_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::deleting(function (Diary $diary): void {
            $diary->deleteStoredImage();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function storeUploadedImage(UploadedFile $image): string
    {
        return $image->store('', 'diary_images');
    }

    public function deleteStoredImage(): void
    {
        if ($this->image_path) {
            Storage::disk('diary_images')->delete($this->image_path);
        }
    }

    /**
     * @return list<string>
     */
    public static function moodValues(): array
    {
        return array_keys(self::MOODS);
    }

    public function moodLabel(): string
    {
        if (! $this->mood) {
            return '';
        }

        return self::MOODS[$this->mood]['label'] ?? '';
    }

    public function moodEmoji(): string
    {
        if (! $this->mood) {
            return '';
        }

        return self::MOODS[$this->mood]['emoji'] ?? '';
    }
}
