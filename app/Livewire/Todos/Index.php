<?php

declare(strict_types=1);

namespace App\Livewire\Todos;

use App\Actions\Todos\DestroyTodoAction;
use App\Models\Todo;
use Flux\Flux;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Session;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

final class Index extends Component
{
    use WithPagination;

    #[Session]
    public int $perPage = 10;

    /** @var array<int,string> */
    public array $searchableFields = ['title', 'description'];

    #[Url]
    public string $search = '';

    public string $sortBy = 'type';

    public string $sortDirection = 'desc';

    public int $todo_id = 0;

    public function sort(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }
    }

    #[Computed]
    #[On('reload-todos')]
    public function todos(): LengthAwarePaginator
    {
        return Todo::query()
            ->where('user_id', Auth::user()->id)
            ->when($this->search, function (Builder $query, string $search): void {
                $query->whereAny($this->searchableFields, 'LIKE', "%$search%");
            })
            ->tap(fn (Builder $query) => $this->sortBy !== '' && $this->sortBy !== '0' ? $query->orderBy($this->sortBy, $this->sortDirection) : $query)
            ->paginate($this->perPage);
    }

    public function render(): View
    {
        return view('livewire.todos.index', ['todos' => $this->todos()]);
    }

    public function delete(int $todo_id): void
    {
        $this->todo_id = $todo_id;
        Flux::modal('delete-todo')->show();
    }

    public function destroy(): void
    {
        new DestroyTodoAction()->handle(Auth::user(), $this->todo_id);
        $this->todo_id = 0;
        Flux::toast(text: 'Todo deleted successfully.', heading: 'Todo deleted', variant: 'success');
        Flux::modal('delete-todo')->close();
        $this->dispatch('reload-todos');
    }

    public function edit(int $todo_id): void
    {
        $this->dispatch('edit-todo', $todo_id);
    }
}
