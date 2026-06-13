<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DiaryImageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('diary_images');
    }

    public function test_user_can_create_diary_with_image(): void
    {
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '画像付き日記',
            'diary_date' => '2025-05-19',
            'image' => $image,
        ]);

        $response->assertRedirect(route('diaries.index'));

        $diary = Diary::query()->first();
        $this->assertNotNull($diary->image_path);
        Storage::disk('diary_images')->assertExists($diary->image_path);
    }

    public function test_user_can_create_diary_with_png_image(): void
    {
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('photo.png');

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => 'PNG画像付き日記',
            'diary_date' => '2025-05-19',
            'image' => $image,
        ]);

        $response->assertRedirect(route('diaries.index'));
        Storage::disk('diary_images')->assertExists(Diary::query()->first()->image_path);
    }

    public function test_user_can_create_diary_with_webp_image(): void
    {
        $user = User::factory()->create();

        if (! function_exists('imagecreatetruecolor')) {
            $this->markTestSkipped('GD extension is required for webp image fakes.');
        }

        $image = UploadedFile::fake()->image('photo.webp');

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => 'WebP画像付き日記',
            'diary_date' => '2025-05-19',
            'image' => $image,
        ]);

        $response->assertRedirect(route('diaries.index'));
        Storage::disk('diary_images')->assertExists(Diary::query()->first()->image_path);
    }

    public function test_invalid_image_extension_is_rejected(): void
    {
        $user = User::factory()->create();
        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '不正ファイル',
            'diary_date' => '2025-05-19',
            'image' => $file,
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_image_larger_than_2mb_is_rejected(): void
    {
        $user = User::factory()->create();
        $image = UploadedFile::fake()->image('large.jpg')->size(2049);

        $response = $this->actingAs($user)->post(route('diaries.store'), [
            'body' => '大きすぎる画像',
            'diary_date' => '2025-05-19',
            'image' => $image,
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_user_can_view_own_diary_image(): void
    {
        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('photo.jpg')->store('', 'diary_images');
        $diary = Diary::factory()->for($user)->create([
            'image_path' => $path,
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.image', $diary));

        $response->assertOk();
    }

    public function test_user_cannot_view_other_users_diary_image(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $path = UploadedFile::fake()->image('photo.jpg')->store('', 'diary_images');
        $diary = Diary::factory()->for($otherUser)->create([
            'image_path' => $path,
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.image', $diary));

        $response->assertForbidden();
    }

    public function test_old_image_is_deleted_when_replaced(): void
    {
        $user = User::factory()->create();
        $oldPath = UploadedFile::fake()->image('old.jpg')->store('', 'diary_images');
        $diary = Diary::factory()->for($user)->create([
            'image_path' => $oldPath,
            'diary_date' => '2025-05-19',
        ]);

        $newImage = UploadedFile::fake()->image('new.jpg');

        $response = $this->actingAs($user)->put(route('diaries.update', $diary), [
            'body' => $diary->body,
            'diary_date' => '2025-05-19',
            'image' => $newImage,
        ]);

        $response->assertRedirect(route('diaries.index'));
        Storage::disk('diary_images')->assertMissing($oldPath);

        $diary->refresh();
        Storage::disk('diary_images')->assertExists($diary->image_path);
        $this->assertNotSame($oldPath, $diary->image_path);
    }

    public function test_image_is_deleted_when_diary_is_deleted(): void
    {
        $user = User::factory()->create();
        $path = UploadedFile::fake()->image('photo.jpg')->store('', 'diary_images');
        $diary = Diary::factory()->for($user)->create([
            'image_path' => $path,
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->delete(route('diaries.destroy', $diary));

        $response->assertRedirect(route('diaries.index'));
        Storage::disk('diary_images')->assertMissing($path);
        $this->assertDatabaseMissing('diaries', ['id' => $diary->id]);
    }
}
