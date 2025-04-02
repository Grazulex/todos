<?php

declare(strict_types=1);

namespace App\Livewire\Todos;

use App\Actions\Todos\CreateTodoAction;
use App\Enums\TypeTodoEnum;
use App\Http\Requests\Todos\CreateTodoRequest;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Livewire\Component;

final class Create extends Component
{
    public string $title = '';

    public string $description = '';

    public TypeTodoEnum $type = TypeTodoEnum::NORMAL;

    public function render(): View
    {
        return view('livewire.todos.create',
            [
                'types' => TypeTodoEnum::cases(),
            ]);
    }

    public function create(CreateTodoAction $action): void
    {
        $this->validate();
        $action->handle(auth()->user(), $this->toArray());
        $this->reset(['title', 'description', 'type']);
        Flux::toast(text: 'Todo created successfully.', heading: 'Todo created', variant: 'success');
        Flux::modal('create-todo')->close();
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
        return new CreateTodoRequest()->rules();
    }
}
