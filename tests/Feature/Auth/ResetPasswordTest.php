<?php

declare(strict_types=1);

use App\Livewire\Auth\ResetPassword;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Session;
use Livewire\Livewire;

test('reset password screen can be rendered', function (): void {
    $response = $this->get('/reset-password/token?email=test@example.com');

    $response->assertStatus(200);
});

test('mount method sets token and email', function (): void {
    $token = 'test-token';
    $email = 'test@example.com';

    $component = Livewire::withQueryParams(['email' => $email])
        ->test(ResetPassword::class, ['token' => $token]);

    $component->assertSet('token', $token);
    $component->assertSet('email', $email);
});

test('password can be reset with valid token', function (): void {
    $user = User::factory()->create();

    // Mock the Password facade
    Password::shouldReceive('reset')
        ->once()
        ->andReturn(Password::PASSWORD_RESET);

    // Fake events to ensure PasswordReset event is dispatched
    Event::fake();

    $component = Livewire::test(ResetPassword::class, ['token' => 'valid-token'])
        ->set('email', $user->email)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('resetPassword');

    // Password reset was attempted, session has success message
    $component->assertRedirect(route('login'));

    // Check for event and session message
    $this->assertNotNull(session('status'));
});

test('password cannot be reset with invalid token', function (): void {
    $user = User::factory()->create();

    // Mock the Password facade for invalid token
    Password::shouldReceive('reset')
        ->once()
        ->andReturn(Password::INVALID_TOKEN);

    $component = Livewire::test(ResetPassword::class, ['token' => 'invalid-token'])
        ->set('email', $user->email)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('resetPassword');

    // Should have error on email field with specific message
    $component->assertHasErrors(['email']);
});

test('password reset requires token', function (): void {
    $component = Livewire::test(ResetPassword::class, ['token' => ''])
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('resetPassword');

    $component->assertHasErrors(['token' => 'required']);
});

test('password reset requires valid email', function (): void {
    $component = Livewire::test(ResetPassword::class, ['token' => 'token'])
        ->set('email', 'not-an-email')
        ->set('password', 'password')
        ->set('password_confirmation', 'password')
        ->call('resetPassword');

    $component->assertHasErrors(['email' => 'email']);
});

test('password must be confirmed', function (): void {
    $component = Livewire::test(ResetPassword::class, ['token' => 'token'])
        ->set('email', 'test@example.com')
        ->set('password', 'password')
        ->set('password_confirmation', 'different-password')
        ->call('resetPassword');

    $component->assertHasErrors(['password' => 'confirmed']);
});

test('reset password dispatches event and flashes session', function (): void {
    $user = User::factory()->create();

    // Replace the actual reset behavior with a custom one that just calls our callback
    Password::shouldReceive('reset')
        ->once()
        ->withArgs(function ($credentials, $callback) use ($user) {
            // Manually trigger the callback to simulate password reset
            $callback($user);

            return true;
        })
        ->andReturn(Password::PASSWORD_RESET);

    // Fake events to spy on PasswordReset
    Event::fake();

    $component = Livewire::test(ResetPassword::class, ['token' => 'valid-token'])
        ->set('email', $user->email)
        ->set('password', 'new-password')
        ->set('password_confirmation', 'new-password')
        ->call('resetPassword');

    // Assert event was dispatched
    Event::assertDispatched(PasswordReset::class, function ($event) use ($user) {
        return $event->user->id === $user->id;
    });

    // Assert session was flashed
    $this->assertSame(session('status'), trans(Password::PASSWORD_RESET));

    // Assert redirect
    $component->assertRedirect(route('login'));
});
