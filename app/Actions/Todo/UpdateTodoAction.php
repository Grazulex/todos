<?php

declare(strict_types=1);

namespace App\Actions\Todo;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateTodoAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, int $todo_id, array $attributes): Todo
    {
        // broadcast(new TodoUpdated($todo))->toOthers();

        return DB::transaction(function () use ($user, $todo_id, $attributes) {
            $todo = Todo::where([
                'user_id' => $user->id,
                'id' => $todo_id,
            ])->firstOrFail();

            $todo->update([
                'type' => $attributes['type'],
                'title' => $attributes['title'],
                'description' => $attributes['description'],
            ]);

            return $todo->refresh();
        });
    }
}
