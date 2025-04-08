<?php

declare(strict_types=1);

use App\Actions\Todos\CreateTodoAction;
use App\Enums\TypeTodoEnum;
use App\Models\User;

test('can create new todo', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $action = new CreateTodoAction();

    $attributes = [
        'title' => 'test',
        'description' => 'test',
        'type' => TypeTodoEnum::NORMAL,
    ];

    $todo = $action->handle($user, $attributes);
});
