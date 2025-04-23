<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;
use App\Livewire\Todos\Create;
use App\Livewire\Todos\Edit;
use App\Models\Todo;
use App\Models\User;
use Livewire\Livewire;

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

test('edit method loads todo data correctly', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Todo to Edit',
        'description' => 'This will be edited',
        'type' => TypeTodoEnum::NORMAL,
    ]);

    Livewire::test(Edit::class)
        ->call('edit', $todo->id)
        ->assertSet('todo_id', $todo->id)
        ->assertSet('title', 'Todo to Edit')
        ->assertSet('description', 'This will be edited')
        ->assertSet('type', TypeTodoEnum::NORMAL);
});
test('cannot edit another users todo', function (): void {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $this->actingAs($user1);

    // Create todo for user2
    $todo = Todo::factory()->create([
        'user_id' => $user2->id,
        'title' => 'User2 Todo',
    ]);

    // User1 tries to edit user2's todo
    expect(function () use ($todo) {
        Livewire::test(Edit::class)
            ->call('edit', $todo->id);
    })->toThrow(Illuminate\Database\Eloquent\ModelNotFoundException::class);
});
test('cannot edit non-existent todo', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $nonExistentId = 9999;

    expect(function () use ($nonExistentId) {
        Livewire::test(Edit::class)
            ->call('edit', $nonExistentId);
    })->toThrow(Illuminate\Database\Eloquent\ModelNotFoundException::class);
});

test('update method validates input', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Valid Todo',
    ]);

    // Empty title should fail validation
    Livewire::test(Edit::class, ['todo_id' => $todo->id])
        ->set('title', '')
        ->set('description', 'Description')
        ->call('update')
        ->assertHasErrors(['title' => 'required']);

    // Title too long should fail validation
    Livewire::test(Edit::class, ['todo_id' => $todo->id])
        ->set('title', str_repeat('a', 256)) // 256 chars, max is 255
        ->call('update')
        ->assertHasErrors(['title' => 'max']);
});

test('update method dispatches events and shows toast', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Original Title',
    ]);

    Livewire::test(Edit::class, ['todo_id' => $todo->id])
        ->set('title', 'Updated Title')
        ->set('description', 'Updated Description')
        ->set('type', TypeTodoEnum::IMPORTANT)
        ->call('update')
        ->assertDispatched('reload-todos')
        ->assertSet('title', '') // Fields should be reset
        ->assertSet('description', '')
        ->assertSet('type', TypeTodoEnum::NORMAL);

    // Verify the todo was updated in the database
    $updatedTodo = Todo::find($todo->id);
    expect($updatedTodo->title)->toBe('Updated Title');
    expect($updatedTodo->description)->toBe('Updated Description');
    expect($updatedTodo->type)->toBe(TypeTodoEnum::IMPORTANT);
});

test('can update todo with different types', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $todo = Todo::factory()->create([
        'user_id' => $user->id,
        'title' => 'Type Test Todo',
        'type' => TypeTodoEnum::NORMAL,
    ]);

    // Test updating to IMPORTANT type
    Livewire::test(Edit::class, ['todo_id' => $todo->id])
        ->set('title', 'Important Todo')
        ->set('description', 'This is important')
        ->set('type', TypeTodoEnum::IMPORTANT)
        ->call('update');

    expect($todo->fresh()->type)->toBe(TypeTodoEnum::IMPORTANT);

    // Test updating to URGENT type
    Livewire::test(Edit::class, ['todo_id' => $todo->id])
        ->set('title', 'Urgent Todo')
        ->set('description', 'This is urgent')
        ->set('type', TypeTodoEnum::URGENT)
        ->call('update');

    expect($todo->fresh()->type)->toBe(TypeTodoEnum::URGENT);
});
