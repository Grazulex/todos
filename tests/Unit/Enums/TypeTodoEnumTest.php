<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;

test('check enum cases', function (): void {
    $enum = TypeTodoEnum::class;

    expect($enum::cases())->toBe([
        TypeTodoEnum::LOW,
        TypeTodoEnum::NORMAL,
        TypeTodoEnum::IMPORTANT,
        TypeTodoEnum::URGENT,
    ]);
});

test('check enum labels', function (): void {
    expect(TypeTodoEnum::LOW->label())->toBe('Low');
    expect(TypeTodoEnum::NORMAL->label())->toBe('Normal');
    expect(TypeTodoEnum::IMPORTANT->label())->toBe('Important');
    expect(TypeTodoEnum::URGENT->label())->toBe('Urgent');
});

test('check enum colors', function (): void {
    expect(TypeTodoEnum::LOW->color())->toBe('blue');
    expect(TypeTodoEnum::NORMAL->color())->toBe('green');
    expect(TypeTodoEnum::IMPORTANT->color())->toBe('orange');
    expect(TypeTodoEnum::URGENT->color())->toBe('red');
});
