<?php

declare(strict_types=1);

use App\Livewire\Actions\Logout;
use App\Livewire\Auth\VerifyEmail;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('can render verify email component', function (): void {
    $user = User::factory()->unverified()->create();
    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->assertStatus(200);
});

test('verified user is redirected when attempting to send verification', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->call('sendVerification')
        ->assertRedirect(route('dashboard', absolute: false));
});

test('unverified user receives verification email', function (): void {
    Notification::fake();

    $user = User::factory()->unverified()->create();
    $this->actingAs($user);

    Livewire::test(VerifyEmail::class)
        ->call('sendVerification')
        ->assertHasNoErrors();

    // Verify that the notification was sent to the user
    Notification::assertSentTo($user, VerifyEmailNotification::class);
});

test('user can logout', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Verify user is logged in before the test
    expect(Auth::check())->toBeTrue();

    // Call the logout method
    $component = Livewire::test(VerifyEmail::class)
        ->call('logout')
        ->assertRedirect('/');

    // Verify user is logged out
    expect(Auth::check())->toBeFalse();
});
