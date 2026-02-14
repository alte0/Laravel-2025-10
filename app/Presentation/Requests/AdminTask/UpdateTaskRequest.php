<?php

namespace App\Presentation\Requests\AdminTask;

use App\Infrastructure\Repositories\Eloquent\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', Task::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'int'],
            'title' => ['required', 'string', 'min:3'],
            'description' => ['required', 'string', 'min:3'],
            'start' => ['date'],
            'end' => ['date'],
        ];
    }
}
