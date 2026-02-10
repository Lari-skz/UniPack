<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'exists:categories,id'],
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['sometimes', 'required', 'date'],
            'priority' => ['sometimes', 'required', 'in:low,medium,high'],
            'status' => ['sometimes', 'required', 'in:pending,in_progress,completed'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Task title is required',
            'due_date.date' => 'Due date must be a valid date',
            'priority.in' => 'Priority must be low, medium, or high',
            'status.in' => 'Status must be pending, in_progress, or completed',
            'category_id.exists' => 'Selected category does not exist',
        ];
    }
}
