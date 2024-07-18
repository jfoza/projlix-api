<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Enums\MessagesEnum;
use App\Shared\Rules\Uuid4Rule;
use Illuminate\Contracts\Validation\Validator;

class SectionsFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'projectId'         => ['nullable', 'string', new Uuid4Rule],
            'projectUniqueName' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'projectId'         => 'Project Id',
            'projectUniqueName' => 'Project Unique Name',
        ];
    }

    protected function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            if (is_null($this->projectId) && is_null($this->projectUniqueName))
            {
                $validator->errors()->add('projectId', MessagesEnum::PROJECT_ID_OR_PROJECT_UNIQUE_NAME_REQUIRED->value);
                $validator->errors()->add('projectUniqueName', MessagesEnum::PROJECT_ID_OR_PROJECT_UNIQUE_NAME_REQUIRED->value);
            }
        });
    }
}
