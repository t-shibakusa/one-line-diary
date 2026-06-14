<?php

use App\Models\User;

it('redirects guests to login from root', function () {
    $this->get('/')->assertRedirect(route('login'));
});

it('redirects authenticated users to home from root', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertRedirect(route('diaries.index'));
});

it('shows login page for guests', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('ログイン');
});

it('allows users to login and reach home', function () {
    User::factory()->create([
        'email' => 'pest@example.com',
    ]);

    $this->post(route('login'), [
        'email' => 'pest@example.com',
        'password' => 'password',
    ])->assertRedirect(route('diaries.index'));

    $this->get(route('diaries.index'))
        ->assertOk()
        ->assertSee('今日の一行を書く')
        ->assertSee('最近の日記');
});
