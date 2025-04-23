<?php

declare(strict_types=1);

use App\Enums\TypeTodoEnum;
use App\Http\Requests\Todos\EditTodoRequest;

test('authorize method always returns true', function (): void {
    $request = new EditTodoRequest();

    expect($request->authorize())->toBeTrue();
});

test('validation rules contain required fields', function (): void {
    $request = new EditTodoRequest();
    $rules = $request->rules();

    // Check title rules
    expect($rules)->toHaveKey('title');
    expect($rules['title'])->toContain('required');
    expect($rules['title'])->toContain('string');
    expect($rules['title'])->toContain('max:255');

    // Check description rules
    expect($rules)->toHaveKey('description');
    expect($rules['description'])->toContain('nullable');
    expect($rules['description'])->toContain('string');
    expect($rules['description'])->toContain('max:1000');
});

test('title is required', function (): void {
    $request = new EditTodoRequest();
    $validator = validator()->make(
        ['description' => 'Some description'],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
});

test('title has max length', function (): void {
    $request = new EditTodoRequest();
    $validator = validator()->make(
        [
            'title' => str_repeat('a', 256), // One more than max
            'description' => 'Some description',
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('title'))->toBeTrue();
});

test('description is optional', function (): void {
    $request = new EditTodoRequest();
    $validator = validator()->make(
        [
            'title' => 'Test Title',
            'type' => TypeTodoEnum::NORMAL,
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeFalse();
});

test('description has max length', function (): void {
    $request = new EditTodoRequest();
    $validator = validator()->make(
        [
            'title' => 'Test Title',
            'description' => str_repeat('a', 1001), // One more than max
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('description'))->toBeTrue();
});

test('type field must be a valid enum value', function (): void {
    $request = new EditTodoRequest();

    // Test with valid enum value
    $validatorValid = validator()->make(
        [
            'title' => 'Test Title',
            'type' => TypeTodoEnum::NORMAL->value,
        ],
        $request->rules()
    );
    expect($validatorValid->fails())->toBeFalse();

    // Test with invalid enum value
    $validatorInvalid = validator()->make(
        [
            'title' => 'Test Title',
            'type' => 'invalid-type',
        ],
        $request->rules()
    );
    expect($validatorInvalid->fails())->toBeTrue();
    expect($validatorInvalid->errors()->has('type'))->toBeTrue();
});

test('type field is required', function (): void {
    $request = new EditTodoRequest();
    $validator = validator()->make(
        [
            'title' => 'Test Title',
            'description' => 'Test Description',
        ],
        $request->rules()
    );

    expect($validator->fails())->toBeTrue();
    expect($validator->errors()->has('type'))->toBeTrue();
});
