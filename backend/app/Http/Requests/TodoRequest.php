<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TodoRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'boolean'
        ];
    }

    public function message():array
    {
        return [
            'title.required' => 'the todo title is required',
            'title.max' => 'the todo title may not be greater than : max charcters.',
            'completed.boolean' => 'the completed field must be true or false.',

        ];
    }


    public function attributes(): array{
        return [
            'title' => 'todo title',
            'description' => 'todo description',
            'completed' => 'completion status',
        ];
    }
}
