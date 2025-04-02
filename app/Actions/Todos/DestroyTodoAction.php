<?php

declare(strict_types=1);

namespace App\Actions\Todos;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class DestroyTodoAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, int $todo_id): void
    {
        // broadcast(new TodoDeleted($todo))->toOthers();

        DB::transaction(function () use ($user, $todo_id): void {
            Todo::where([
                'user_id' => $user->id,
                'id' => $todo_id,
            ])->delete();
        });
    }
}
