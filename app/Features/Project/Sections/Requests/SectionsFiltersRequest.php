<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class SectionsFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'projectId' => ['required', 'string', new Uuid4Rule],
        ];
    }

    public function attributes(): array
    {
        return [
            'projectId' => 'Project Id',
        ];
    }
}
