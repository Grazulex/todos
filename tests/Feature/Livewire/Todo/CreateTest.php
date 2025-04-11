<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;
use App\Livewire\Todos\Create;
use App\Models\Todo;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function (): void {
    Livewire::test(Create::class)
        ->assertStatus(200);
});

test('it can create a todo', function (): void {
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
});
