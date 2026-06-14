<?php

namespace App\Models;

use App\Models\Concerns\HasPlantGrowth;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasPlantGrowth, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'avatar_path',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function diaries(): HasMany
    {
        return $this->hasMany(Diary::class);
    }

    protected static function booted(): void
    {
        static::deleting(function (User $user): void {
            $user->deleteStoredAvatar();
        });
    }

    public function storeUploadedAvatar(UploadedFile $avatar): string
    {
        return $avatar->store('', 'user_avatars');
    }

    public function deleteStoredAvatar(): void
    {
        if ($this->avatar_path) {
            Storage::disk('user_avatars')->delete($this->avatar_path);
        }
    }

    public function hasAvatar(): bool
    {
        return $this->avatar_path !== null
            && Storage::disk('user_avatars')->exists($this->avatar_path);
    }
}
