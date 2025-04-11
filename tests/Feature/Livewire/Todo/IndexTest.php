<?php

declare(strict_types=1);

use App\Livewire\Todos\Index;
use App\Models\Todo;
use App\Models\User;
use Livewire\Livewire;

it('renders successfully', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(Index::class)
        ->assertStatus(200);
});

test('can see todos', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::test(Index::class)
        ->assertSee($todo->title)
        ->assertSee($todo->description);
});

test('can destroy todo', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::test(Index::class)
        ->set('todo_id', $todo->id)
        ->call('destroy')
        ->assertStatus(200);

    expect(Todo::find($todo->id))->toBeNull();
});

test('can sort todos list', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo1 = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'AAAAA',
    ]);
    $todo2 = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'BBBBB',
    ]);

    expect(
        Livewire::test(Index::class)
            ->set('sortBy', 'title')
            ->set('sortDirection', 'asc')
            ->set('perPage', '1')
            ->get('todos')->items()[0]->title)
        ->toBe($todo1->title);

    expect(
        Livewire::test(Index::class)
            ->set('sortBy', 'title')
            ->set('sortDirection', 'desc')
            ->set('perPage', '1')
            ->get('todos')->items()[0]->title)
        ->toBe($todo2->title);
});

test('can dispatch when editing todo', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::test(Index::class)
        ->call('edit', $todo->id)
        ->assertDispatched('edit-todo', $todo->id);
});
