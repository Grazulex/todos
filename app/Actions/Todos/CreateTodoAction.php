<?php

declare(strict_types=1);

namespace App\Actions\Todos;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class CreateTodoAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, array $attributes): Todo
    {
        // broadcast(new TodoCreated($todo))->toOthers();

        return DB::transaction(function () use ($user, $attributes) {
            return Todo::create([
                'user_id' => $user->id,
                'type' => $attributes['type'],
                'title' => $attributes['title'],
                'description' => $attributes['description'],
            ]);
        });
    }
}
