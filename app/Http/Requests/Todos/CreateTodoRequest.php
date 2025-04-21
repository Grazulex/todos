<?php

declare(strict_types=1);

namespace App\Http\Requests\Todos;

use Illuminate\Foundation\Http\FormRequest;

final class CreateTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            // 'type' => 'required|in:'.new TypeTodoEnum(),
        ];
    }
}
