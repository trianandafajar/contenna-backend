<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBlogRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'special_role' => 'nullable|string|in:1,2,3',
            'status' => 'nullable|string|in:0,1,2',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Blog title is required',
            'title.max' => 'Blog title cannot exceed 255 characters',
            'content.required' => 'Blog content is required',
            'category_id.required' => 'Category is required',
            'category_id.exists' => 'Selected category does not exist',
            'tags.array' => 'Tags must be an array',
            'tags.*.string' => 'Each tag must be a string',
            'tags.*.max' => 'Each tag cannot exceed 100 characters',
            'thumbnail.image' => 'Thumbnail must be an image file',
            'thumbnail.mimes' => 'Thumbnail must be a file of type: jpeg, png, jpg, gif, webp',
            'thumbnail.max' => 'Thumbnail size cannot exceed 2MB',
            'special_role.in' => 'Special role must be 1, 2, or 3',
            'status.in' => 'Status must be 0 (archived), 1 (published), or 2 (draft)',
        ];
    }
}
