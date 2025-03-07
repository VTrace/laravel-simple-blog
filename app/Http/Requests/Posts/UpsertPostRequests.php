<?php

namespace App\Http\Requests\Posts;

use App\Enums\PostStatus;
use Illuminate\Foundation\Http\FormRequest;

class UpsertPostRequests extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow the post author to update
        $post = $this->route('post');
        return $post ? $this->user()->can('update', $post) : true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:60'],
            'body' => ['required', 'string'],
            'scheduled_at' => ['nullable', 'date', 'after_or_equal:today'],
            'is_draft' => ['boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'The title is required.',
            'title.string' => 'The title must be a string.',
            'title.max' => 'The title must not exceed 60 characters.',
            'body.required' => 'The body is required.',
            'body.string' => 'The body must be a string.',
            'scheduled_at.date' => 'The scheduled date must be a valid date.',
            'scheduled_at.after_or_equal' => 'The scheduled publish date cannot be earlier than today.',
            'is_draft.boolean' => 'The draft status must be true or false.',
        ];
    }
}
