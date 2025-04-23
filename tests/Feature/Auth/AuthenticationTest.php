<?php

declare(strict_types=1);

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Livewire;

test('login screen can be rendered', function (): void {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function (): void {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function (): void {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'wrong-password')
        ->call('login');

    $response->assertHasErrors('email');

    $this->assertGuest();
});

test('users can logout', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');

    $this->assertGuest();
});

test('users can be rate limited after too many failed attempts', function (): void {
    Event::fake([Lockout::class]);

    $user = User::factory()->create();
    $email = $user->email;

    // Clear any existing rate limits for this test
    $throttleKey = "throttle_key_{$email}";
    RateLimiter::clear($throttleKey);

    // Try to log in 5 times with wrong password
    for ($i = 0; $i < 5; $i++) {
        Livewire::test(Login::class)
            ->set('email', $email)
            ->set('password', 'wrong-password')
            ->call('login')
            ->assertHasErrors('email');
    }

    // 6th attempt should trigger lockout
    $response = Livewire::test(Login::class)
        ->set('email', $email)
        ->set('password', 'wrong-password')
        ->call('login');

    Event::assertDispatched(Lockout::class);
    $response->assertHasErrors('email');
    $this->assertStringContainsString('Too many login attempts', $response->errors()->get('email')[0]);
});

test('users can authenticate after rate limit expires', function (): void {
    $user = User::factory()->create();
    $email = $user->email;

    // Mock RateLimiter to simulate an expired rate limit
    RateLimiter::shouldReceive('tooManyAttempts')
        ->once()
        ->andReturn(false);

    RateLimiter::shouldReceive('clear')
        ->once();

    // User should be able to log in
    Livewire::test(Login::class)
        ->set('email', $email)
        ->set('password', 'password')
        ->call('login')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('remember me functionality works', function (): void {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->set('remember', true)
        ->call('login');

    $response
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();

    // Check that the remember_web_* cookie is present
    $this->assertNotNull($user->fresh()->getRememberToken());
});
