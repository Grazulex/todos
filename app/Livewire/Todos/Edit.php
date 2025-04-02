<?php

declare(strict_types=1);

namespace App\Livewire\Todos;

use App\Actions\Todos\UpdateTodoAction;
use App\Enums\TypeTodoEnum;
use App\Http\Requests\Todos\EditTodoRequest;
use App\Models\Todo;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

final class Edit extends Component
{
    public int $todo_id = 0;

    public string $title = '';

    public string $description = '';

    public TypeTodoEnum $type = TypeTodoEnum::NORMAL;

    public function render(): View
    {
        return view('livewire.todos.edit',
            [
                'types' => TypeTodoEnum::cases(),
            ]);
    }

    #[On('edit-todo')]
    public function edit(int $todo_id): void
    {
        $todo = Todo::where([
            'user_id' => auth()->user()->id,
            'id' => $todo_id,
        ]
        )->firstOrFail();

        $this->todo_id = $todo_id;
        $this->title = $todo->title;
        $this->description = $todo->description;
        $this->type = $todo->type;

        Flux::modal('edit-todo')->show();
    }

    /**
     * @throws Throwable
     */
    public function update(UpdateTodoAction $action): void
    {
        $this->validate();
        $action->handle(auth()->user(), $this->todo_id, $this->toArray());
        $this->reset(['title', 'description', 'type']);
        Flux::toast(text: 'Todo updated successfully.', heading: 'Todo updated', variant: 'success');
        Flux::modal('edit-todo')->close();
        $this->dispatch('reload-todos');
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type->value,
        ];
    }

    protected function rules(): array
    {
        return new EditTodoRequest()->rules();
    }
}
