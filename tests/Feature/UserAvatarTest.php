<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserAvatarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('user_avatars');
    }

    public function test_settings_page_displays_avatar_upload_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertOk();
        $response->assertSee('プロフィール画像');
        $response->assertSee('jpg / jpeg / png / webp');
    }

    public function test_user_can_upload_avatar(): void
    {
        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)->put(route('settings.avatar.update'), [
            'avatar' => $avatar,
        ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertSessionHas('status');

        $user->refresh();
        $this->assertNotNull($user->avatar_path);
        Storage::disk('user_avatars')->assertExists($user->avatar_path);
    }

    public function test_user_can_upload_png_avatar(): void
    {
        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.png');

        $response = $this->actingAs($user)->put(route('settings.avatar.update'), [
            'avatar' => $avatar,
        ]);

        $response->assertRedirect(route('settings.index'));
        Storage::disk('user_avatars')->assertExists($user->fresh()->avatar_path);
    }

    public function test_user_can_upload_webp_avatar(): void
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is required for webp image fakes.');
        }

        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.webp');

        $response = $this->actingAs($user)->put(route('settings.avatar.update'), [
            'avatar' => $avatar,
        ]);

        $response->assertRedirect(route('settings.index'));
        Storage::disk('user_avatars')->assertExists($user->fresh()->avatar_path);
    }

    public function test_invalid_avatar_format_is_rejected(): void
    {
        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->create('avatar.gif', 100, 'image/gif');

        $response = $this->actingAs($user)->put(route('settings.avatar.update'), [
            'avatar' => $avatar,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_avatar_larger_than_2mb_is_rejected(): void
    {
        $user = User::factory()->create();
        $avatar = UploadedFile::fake()->image('avatar.jpg')->size(2049);

        $response = $this->actingAs($user)->put(route('settings.avatar.update'), [
            'avatar' => $avatar,
        ]);

        $response->assertSessionHasErrors('avatar');
    }

    public function test_user_can_view_own_avatar(): void
    {
        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('avatar.jpg')->store('', 'user_avatars');
        $user->update(['avatar_path' => $path]);

        $response = $this->actingAs($user)->get(route('settings.avatar'));

        $response->assertOk();
    }

    public function test_guest_cannot_view_avatar(): void
    {
        $response = $this->get(route('settings.avatar'));

        $response->assertRedirect(route('login'));
    }

    public function test_old_avatar_is_deleted_when_replaced(): void
    {
        $user = User::factory()->create();
        $oldPath = UploadedFile::fake()->image('old.jpg')->store('', 'user_avatars');
        $user->update(['avatar_path' => $oldPath]);

        $newAvatar = UploadedFile::fake()->image('new.jpg');

        $this->actingAs($user)->put(route('settings.avatar.update'), [
            'avatar' => $newAvatar,
        ]);

        Storage::disk('user_avatars')->assertMissing($oldPath);
        Storage::disk('user_avatars')->assertExists($user->fresh()->avatar_path);
    }

    public function test_avatar_is_deleted_when_user_is_deleted(): void
    {
        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('avatar.jpg')->store('', 'user_avatars');
        $user->update(['avatar_path' => $path]);

        $user->delete();

        Storage::disk('user_avatars')->assertMissing($path);
    }
}
