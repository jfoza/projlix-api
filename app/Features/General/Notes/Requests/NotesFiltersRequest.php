<?php

namespace App\Features\General\Notes\Requests;

use App\Features\Base\Requests\FormRequest;

class NotesFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules();
    }

    public function attributes(): array
    {
         return $this->mergePaginationOrderAttributes();
    }
}
