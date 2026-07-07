<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreTimeLogRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'work_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],

            'project_id' => [
                'required',
                'exists:projects,id',
            ],

            'task_description' => [
                'required',
                'string',
                'max:500',
            ],

            'time' => [
                'required',
                'regex:/^(0?[0-9]|10):([0-5][0-9])$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'work_date.before_or_equal' => 'Future dates are not allowed.',
            'time.regex' => 'Time must be in HH:MM format (e.g. 02:30).',
        ];
    }
}
