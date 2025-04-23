<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;
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

test('delete method shows modal', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::test(Index::class)
        ->call('delete', $todo->id)
        ->assertSet('todo_id', $todo->id);

    // Since we can't directly test Flux::modal in the unit test, we verify the todo_id was set properly
    // which is what happens when the delete modal is shown
});

test('destroy method resets todo_id and dispatches event', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
    ]);

    Livewire::test(Index::class)
        ->set('todo_id', $todo->id)
        ->call('destroy')
        ->assertSet('todo_id', 0)  // Verify todo_id was reset
        ->assertDispatched('reload-todos');

    // Verify the todo was actually deleted
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

test('can toggle sort direction on same column', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Todo::factory()->count(2)->create([
        'user_id' => $user->id,
    ]);

    $component = Livewire::test(Index::class)
        ->set('sortBy', 'title')
        ->set('sortDirection', 'asc');

    // Call sort on the same column - should toggle to desc
    $component->call('sort', 'title')
        ->assertSet('sortDirection', 'desc');

    // Call sort again on the same column - should toggle back to asc
    $component->call('sort', 'title')
        ->assertSet('sortDirection', 'asc');
});

test('can change sort column', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    Todo::factory()->count(2)->create([
        'user_id' => $user->id,
    ]);

    // Start with title/desc
    $component = Livewire::test(Index::class)
        ->set('sortBy', 'title')
        ->set('sortDirection', 'desc');

    // Change sort to a different column
    $component->call('sort', 'description')
        ->assertSet('sortBy', 'description')
        ->assertSet('sortDirection', 'asc');  // Direction should be reset to asc
});

test('search filters todos by title', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create todos with specific titles
    $matchingTodo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Find this specific todo',
        'description' => 'Regular description',
    ]);

    $nonMatchingTodo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Another todo',
        'description' => 'Different text',
    ]);

    // Search for "specific" in title
    $component = Livewire::test(Index::class)
        ->set('search', 'specific');

    // Should find the matching todo but not the non-matching one
    $todoItems = $component->get('todos')->items();
    expect($todoItems)->toHaveCount(1);
    expect($todoItems[0]->id)->toBe($matchingTodo->id);
});

test('search filters todos by description', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create todos with specific descriptions
    $matchingTodo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Regular title',
        'description' => 'Find this in the description',
    ]);

    $nonMatchingTodo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Another todo',
        'description' => 'Different text',
    ]);

    // Search for text in description
    $component = Livewire::test(Index::class)
        ->set('search', 'Find this');

    // Should find the matching todo but not the non-matching one
    $todoItems = $component->get('todos')->items();
    expect($todoItems)->toHaveCount(1);
    expect($todoItems[0]->id)->toBe($matchingTodo->id);
});

test('search returns empty when no matches', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create some todos
    Todo::factory()->count(3)->create([
        'user_id' => $user->id,
    ]);

    // Search for something that won't match
    $component = Livewire::test(Index::class)
        ->set('search', 'xyz123NonExistentText');

    // Should find no todos
    $todoItems = $component->get('todos');
    expect($todoItems->total())->toBe(0);
});

test('pagination works with search and sort', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    // Create 15 todos with "test" in the title
    collect(range(1, 15))->each(function ($i) use ($user) {
        Todo::factory()->create([
            'user_id' => $user->id,
            'title' => "test todo {$i}",
            'type' => TypeTodoEnum::NORMAL,
        ]);
    });

    // Set perPage to 10 and search for "test"
    $component = Livewire::test(Index::class)
        ->set('perPage', 10)
        ->set('search', 'test')
        ->set('sortBy', 'title')
        ->set('sortDirection', 'asc');

    // First page should have 10 todos
    expect($component->get('todos')->items())->toHaveCount(10);
    expect($component->get('todos')->total())->toBe(15);
    expect($component->get('todos')->hasPages())->toBeTrue();
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
