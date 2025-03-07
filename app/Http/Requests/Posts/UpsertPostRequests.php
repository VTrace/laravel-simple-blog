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
            'title' => 'required|string|max:60',
            'body' => 'required|string',
            // 'status' => 'required|in:' . implode(',', array_column(PostStatus::cases(), 'value')),
            'scheduled_at' => 'nullable|date|after:now',
            'is_draft' => 'boolean'
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'title.string' => 'The title must be a valid string.',
            'title.max' => 'The title must not exceed 60 characters.',

            'body.required' => 'The body field is required.',
            'body.string' => 'The body must be a valid string.',

            // 'status.required' => 'The status field is required.',
            // 'status.in' => 'The selected status is invalid.',

            'scheduled_at.date' => 'The scheduled publish date must be a valid date.',
            'scheduled_at.after' => 'The scheduled publish date must be in the future.',

            'is_draft.boolean' => 'The draft status must be true or false.',
        ];
    }
}
