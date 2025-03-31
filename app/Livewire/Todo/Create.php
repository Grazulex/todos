<?php

declare(strict_types=1);

namespace App\Livewire\Todo;

use App\Enums\TypeTodoEnum;
use App\Models\Todo;
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

    public function create(): void
    {
        $this->validate();

        Todo::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'user_id' => auth()->id(),
        ]);

        $this->reset(['title', 'description', 'type']);

        session()->flash('message', 'Todo created successfully.');

        Flux::modal('create-todo')->close();

        $this->dispatch('reload-todos');
    }
}
