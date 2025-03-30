<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\TypeTodoEnum;
use Carbon\CarbonImmutable;
use Database\Factories\TodoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read int $id
 * @property-read string $title
 * @property-read string|null $description
 * @property-read int $user_id
 * @property-read TypeTodoEnum $type
 * @property-read CarbonImmutable $created_at
 * @property-read CarbonImmutable $updated_at
 * @property-read User $user
 */
final class Todo extends Model
{
    /** @use HasFactory<TodoFactory> */
    use HasFactory;

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'type' => TypeTodoEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
