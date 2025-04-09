<?php

declare(strict_types=1);

use App\Actions\Todos\DestroyTodoAction;
use App\Models\Todo;
use App\Models\User;

test('can delete todo', function (): void {
    $user = User::factory()->create();
    $todo = Todo::factory()->create(
        ['user_id' => $user->id]
    );

    $action = app(DestroyTodoAction::class);

    $action->handle($user, $todo->id);

    expect(Todo::find($todo->id))->toBeNull();
});
