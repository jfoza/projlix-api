<?php

namespace App\Features\Project\Projects\Requests;

use App\Features\Base\Requests\FormRequest;

class ProjectsFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return $this->mergePaginationOrderRules(
            [
                'name' => 'nullable|string',
            ]
        );
    }

    public function attributes(): array
    {
         return $this->mergePaginationOrderAttributes(
             [
                 'name' => 'Name',
             ]
         );
    }
}
