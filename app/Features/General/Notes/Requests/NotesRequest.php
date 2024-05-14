<?php

namespace App\Features\General\Notes\Requests;

use App\Features\Base\Requests\FormRequest;

class NotesRequest extends FormRequest
{
    public array $sorting = [];

    public function authorize(): true
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content' => 'required|string',
        ];
    }

    public function attributes(): array
    {
         return [
             'content' => 'Content',
         ];
    }
}
