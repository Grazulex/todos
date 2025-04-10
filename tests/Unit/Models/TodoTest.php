<?php

declare(strict_types=1);

use App\Models\Todo;
use App\Models\User;

test('to array', function (): void {
    $user = Todo::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toEqual([
            'id',
            'title',
            'description',
            'type',
            'user_id',
            'created_at',
            'updated_at',
        ]);
});

it('belongs to a user', function (): void {
    $todo = Todo::factory()->create();

    expect($todo->user)->toBeInstanceOf(User::class);
});
