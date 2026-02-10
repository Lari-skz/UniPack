<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['sometimes', 'required', 'date'],
            'event_time' => ['sometimes', 'required', 'date_format:H:i'],
            'color' => ['sometimes', 'string', 'max:7', 'regex:/^#[0-9A-F]{6}$/i'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required',
            'event_date.date' => 'Event date must be a valid date',
            'event_time.date_format' => 'Event time must be in HH:MM format',
            'color.regex' => 'Color must be a valid hex code',
        ];
    }
}
