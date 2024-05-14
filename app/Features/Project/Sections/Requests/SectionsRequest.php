<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Enums\HttpRequestMethodsEnum;
use App\Shared\Rules\Uuid4Rule;

class SectionsRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'projectId' => ['required', 'string', new Uuid4Rule],
            'colorId'   => ['required', 'string', new Uuid4Rule],
            'iconId'    => ['required', 'string', new Uuid4Rule],
            'name'      => ['required', 'string'],
        ];

        if($this->method() == HttpRequestMethodsEnum::PUT->value)
        {
            $rules['projectId'] = 'nullable';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'projectId' => 'Project Id',
            'colorId'   => 'Color Id',
            'iconId'    => 'Icon Id',
            'name'      => 'Name'
        ];
    }
}
