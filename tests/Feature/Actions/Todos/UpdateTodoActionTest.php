<?php

declare(strict_types=1);

use App\Actions\Todos\UpdateTodoAction;
use App\Enums\TypeTodoEnum;
use App\Models\Todo;
use App\Models\User;

test('can update todo', function (): void {
    $user = User::factory()->create();
    $todo = Todo::factory()->create(
        ['user_id' => $user->id]
    );

    $action = app(UpdateTodoAction::class);

    $todo = $action->handle($user, $todo->id, [
        'title' => 'updated title',
        'description' => 'updated description',
        'type' => TypeTodoEnum::IMPORTANT,
    ]);

    expect($todo->title)->toBe('updated title');
    expect($todo->description)->toBe('updated description');
    expect($todo->type)->toBe(TypeTodoEnum::IMPORTANT);
});
