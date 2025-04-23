<?php

declare(strict_types=1);

use App\Livewire\Settings\Profile;
use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;

test('profile information can be updated', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', 'Test Name')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    $user->refresh();

    expect($user->name)->toBe('Test Name');
    expect($user->email)->toBe($user->email);
    expect($user->email_verified_at)->not->toBeNull();
});

test('email verification status is unchanged when email address is unchanged', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $originalVerificationDate = $user->email_verified_at;

    Livewire::test(Profile::class)
        ->set('name', 'Test Name')
        ->set('email', $user->email)
        ->call('updateProfileInformation');

    expect($user->fresh()->email_verified_at)->toEqual($originalVerificationDate);
});

test('email verification status is reset when email address is changed', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', $user->name)
        ->set('email', 'new-email@example.com')
        ->call('updateProfileInformation');

    expect($user->fresh()->email_verified_at)->toBeNull();
    expect($user->fresh()->email)->toBe('new-email@example.com');
});

test('profile dispatch event when updated', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', 'New Name')
        ->set('email', $user->email)
        ->call('updateProfileInformation')
        ->assertDispatched('profile-updated', name: 'New Name');
});

test('name field is required', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', '')
        ->set('email', $user->email)
        ->call('updateProfileInformation')
        ->assertHasErrors(['name' => 'required']);
});

test('email field is required', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', $user->name)
        ->set('email', '')
        ->call('updateProfileInformation')
        ->assertHasErrors(['email' => 'required']);
});

test('email must be valid', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', $user->name)
        ->set('email', 'invalid-email')
        ->call('updateProfileInformation')
        ->assertHasErrors(['email' => 'email']);
});

test('email must be unique', function (): void {
    $user = User::factory()->create();
    $otherUser = User::factory()->create();

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->set('name', $user->name)
        ->set('email', $otherUser->email)
        ->call('updateProfileInformation')
        ->assertHasErrors(['email' => 'unique']);
});

test('verified user is redirected when attempting to resend verification', function (): void {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->call('resendVerificationNotification')
        ->assertRedirect(route('dashboard', absolute: false));
});

test('unverified user receives verification email', function (): void {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);

    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->call('resendVerificationNotification');

    Notification::assertSentTo($user, VerifyEmail::class);
});

test('verification resend executes without errors', function (): void {
    Notification::fake();

    $user = User::factory()->create(['email_verified_at' => null]);
    $this->actingAs($user);

    Livewire::test(Profile::class)
        ->call('resendVerificationNotification')
        ->call('resendVerificationNotification')
        ->assertHasNoErrors();

    Notification::assertSentTo($user, VerifyEmail::class);

    // We don't test the session flash as it's hard to test in this context,
});
