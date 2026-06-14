<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_settings(): void
    {
        $response = $this->get(route('settings.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_settings_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertOk();
        $response->assertSee('設定');
        $response->assertSee('パスワード変更');
        $response->assertSee('画面モード');
        $response->assertSee('ライト');
        $response->assertSee('ダーク');
    }

    public function test_settings_page_links_to_password_change_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.index'));

        $response->assertSee(route('settings.password'), false);
    }

    public function test_user_can_switch_to_dark_theme(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('settings.theme'), [
            'theme' => 'dark',
        ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertSessionHas('status');
        $response->assertCookie('theme', 'dark');

        $page = $this->actingAs($user)->withCookie('theme', 'dark')->get(route('settings.index'));
        $page->assertOk();
        $page->assertSee('class="dark"', false);
    }

    public function test_user_can_switch_to_light_theme(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->withCookie('theme', 'dark')
            ->put(route('settings.theme'), [
                'theme' => 'light',
            ]);

        $response->assertRedirect(route('settings.index'));
        $response->assertCookie('theme', 'light');
    }

    public function test_user_can_view_password_change_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.password'));

        $response->assertOk();
        $response->assertSee('パスワード変更');
        $response->assertSee('現在のパスワード');
        $response->assertSee('設定へ戻る');
    }

    public function test_user_can_update_password_from_settings_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->from(route('settings.password'))->put(route('password.update'), [
            'current_password' => 'password',
            'password' => 'new-password-123',
            'password_confirmation' => 'new-password-123',
        ]);

        $response->assertRedirect(route('settings.password'));
        $response->assertSessionHas('status', 'password-updated');
    }
}
