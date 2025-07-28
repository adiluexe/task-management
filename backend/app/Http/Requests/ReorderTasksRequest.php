<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReorderTasksRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tasks' => 'required|array',
            'tasks.*.id' => 'required|integer|exists:tasks,id',
            'tasks.*.order' => 'required|integer|min:0',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'tasks.required' => 'Tasks array is required.',
            'tasks.array' => 'Tasks must be an array.',
            'tasks.*.id.required' => 'Each task must have an ID.',
            'tasks.*.id.exists' => 'One or more task IDs are invalid.',
            'tasks.*.order.required' => 'Each task must have an order value.',
            'tasks.*.order.integer' => 'Order must be an integer.',
            'tasks.*.order.min' => 'Order must be at least 0.',
        ];
    }
}
