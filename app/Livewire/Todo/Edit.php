<?php

declare(strict_types=1);

namespace App\Livewire\Todo;

use App\Actions\Todo\UpdateTodoAction;
use App\Enums\TypeTodoEnum;
use App\Http\Requests\Todo\EditTodoRequest;
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
        return view('livewire.todo.edit',
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

        session()->flash('message', 'Todo updated successfully.');

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
