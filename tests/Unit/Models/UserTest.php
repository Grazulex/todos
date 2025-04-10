<?php

declare(strict_types=1);

use App\Models\Todo;
use App\Models\User;

test('to array', function (): void {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toEqual([
            'id',
            'name',
            'email',
            'email_verified_at',
            'created_at',
            'updated_at',
        ]);
});

it('may have todos', function (): void {
    $user = User::factory()->create();

    Todo::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    expect($user->todos)->toHaveCount(3);
});

it('check initials', function (): void {
    $user = User::factory()->create([
        'name' => 'John Doe',
    ]);

    expect($user->initials())->toEqual('JD');
});
