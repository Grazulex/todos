<?php

declare(strict_types=1);

namespace App\Livewire\Todo;

use App\Actions\Todo\CreateTodoAction;
use App\Enums\TypeTodoEnum;
use App\Http\Requests\Todo\CreateTodoRequest;
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
        return view('livewire.todo.create',
            [
                'types' => TypeTodoEnum::cases(),
            ]);
    }

    public function create(CreateTodoAction $action): void
    {
        $this->validate();

        $action->handle(auth()->user(), $this->toArray());

        $this->reset(['title', 'description', 'type']);

        session()->flash('message', 'Todo created successfully.');

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
