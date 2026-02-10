<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['required', 'date_format:H:i'],
            'color' => ['nullable', 'string', 'max:7', 'regex:/^#[0-9A-F]{6}$/i'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Event title is required',
            'title.max' => 'Event title cannot exceed 255 characters',
            'event_date.required' => 'Event date is required',
            'event_date.date' => 'Event date must be a valid date',
            'event_date.after_or_equal' => 'Event date cannot be in the past',
            'event_time.required' => 'Event time is required',
            'event_time.date_format' => 'Event time must be in HH:MM format',
            'color.regex' => 'Color must be a valid hex code (e.g., #FF006E)',
        ];
    }
}
