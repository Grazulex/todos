<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;
use App\Livewire\Todos\Create;
use App\Livewire\Todos\Edit;
use App\Models\Todo;
use App\Models\User;

it('render successfully', function () {
    Livewire::test(Edit::class, [
        'todo_id' => Todo::factory()->create()->id,
    ])
        ->assertStatus(200);
});

test('it can create and edit a todo', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Create::class)
        ->set('type', TypeTodoEnum::NORMAL)
        ->set('title', 'Test Todo')
        ->set('description', 'This is a test todo.')
        ->call('create');

    $todo = Todo::where('title', 'Test Todo')->first();

    expect($todo)->toBeInstanceOf(Todo::class);
    expect($todo->user_id)->toBe($user->id);
    expect($todo->title)->toBe('Test Todo');
    expect($todo->description)->toBe('This is a test todo.');
    expect($todo->type)->toBe(TypeTodoEnum::NORMAL);

    Livewire::test(Edit::class, [
        'todo_id' => $todo->id,
    ])
        ->set('type', TypeTodoEnum::IMPORTANT)
        ->set('title', 'Updated Todo')
        ->set('description', 'This is an updated test todo.')
        ->call('update');

    expect($todo->fresh()->type)->toBe(TypeTodoEnum::IMPORTANT);
    expect($todo->fresh()->title)->toBe('Updated Todo');
    expect($todo->fresh()->description)->toBe('This is an updated test todo.');
});
