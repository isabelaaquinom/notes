<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EditNote extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|min:3|max:200',
            'text' => 'required|min:3|max:3000',
        ];
    }

    public function message(): array
    {
        return
            [
                'text_title.required' => "The title is required",
                'text_title.min' => "The title must be at least :min characters",
                'text_title.max' => "The title must be at most :max characters",

                'text_note.required' => "The note is required",
                'text_note.min' => "The note must be at least :min characters",
                'text_note.max' => "The note must be at most :max characters",
            ];

    }
}
