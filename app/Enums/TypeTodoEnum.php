<?php

declare(strict_types=1);

namespace App\Enums;

enum TypeTodoEnum: string
{
    case LOW = 'low';
    case NORMAL = 'normal';
    case IMPORTANT = 'important';
    case URGENT = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::LOW => 'Low',
            self::NORMAL => 'Normal',
            self::IMPORTANT => 'Important',
            self::URGENT => 'Urgent',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::LOW => 'blue',
            self::NORMAL => 'green',
            self::IMPORTANT => 'orange',
            self::URGENT => 'red',
        };
    }
}
