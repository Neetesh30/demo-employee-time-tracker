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
        $rules = [
            'work_date' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
        ];

        if ($this->filled('tasks') && is_array($this->input('tasks'))) {
            $rules['tasks'] = ['required', 'array', 'min:1'];
            $rules['tasks.*.project_id'] = ['required', 'exists:projects,id'];
            $rules['tasks.*.task_description'] = ['required', 'string', 'max:500'];
            $rules['tasks.*.time'] = ['required', 'regex:/^([0-9]|[0-9]{2}):([0-5][0-9])$/'];
        } else {
            $rules['project_id'] = ['required', 'exists:projects,id'];
            $rules['task_description'] = ['required', 'string', 'max:500'];
            $rules['time'] = ['required', 'regex:/^([0-9]|[0-9]{2}):([0-5][0-9])$/'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'work_date.before_or_equal' => 'Future dates are not allowed.',
            'time.regex' => 'Time must be in HH:MM format (e.g. 02:30).',
        ];
    }
}
