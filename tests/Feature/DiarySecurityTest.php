<?php

namespace Tests\Feature;

use App\Models\Diary;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiarySecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_diary_create_page(): void
    {
        $response = $this->get(route('diaries.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_diary_edit_page(): void
    {
        $diary = Diary::factory()->create();

        $response = $this->get(route('diaries.edit', $diary));

        $response->assertRedirect(route('login'));
    }

    public function test_guest_cannot_access_diary_image(): void
    {
        $diary = Diary::factory()->create([
            'image_path' => 'sample.jpg',
        ]);

        $response = $this->get(route('diaries.image', $diary));

        $response->assertRedirect(route('login'));
    }

    public function test_diary_forms_include_csrf_token(): void
    {
        $user = User::factory()->create();
        $diary = Diary::factory()->for($user)->create();

        $this->actingAs($user)->get(route('diaries.create'))
            ->assertOk()
            ->assertSee('name="_token"', false);

        $this->actingAs($user)->get(route('diaries.edit', $diary))
            ->assertOk()
            ->assertSee('name="_token"', false);
    }

    public function test_diary_body_is_escaped_in_output(): void
    {
        $user = User::factory()->create();
        $xssPayload = '<script>alert("xss")</script>';

        Diary::factory()->for($user)->create([
            'body' => $xssPayload,
            'diary_date' => '2025-05-19',
        ]);

        $response = $this->actingAs($user)->get(route('diaries.index'));

        $response->assertOk();
        $response->assertDontSee($xssPayload, false);
        $response->assertSee(e($xssPayload), false);
    }

    public function test_diary_images_are_stored_on_private_disk(): void
    {
        $root = config('filesystems.disks.diary_images.root');

        $this->assertStringContainsString('private', $root);
        $this->assertStringNotContainsString('public', $root);
    }

    public function test_env_file_is_listed_in_gitignore(): void
    {
        $gitignore = file_get_contents(base_path('.gitignore'));

        $this->assertStringContainsString('.env', $gitignore);
    }
}
