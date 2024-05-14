<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class CardsRequest extends FormRequest
{
    public function rules(): array
    {
        $uuid4          = ['required', 'string', new Uuid4Rule()];
        $requiredUuid4  = ['required', 'string', new Uuid4Rule()];
        $requiredString = ['required', 'string'];
        $nullableDate   = ['nullable', 'date'];

        return [
            'sectionId'   => $uuid4,
            'tagId'       => $requiredUuid4,
            'responsible' => $requiredUuid4,
            'description' => $requiredString,
            'limitDate'   => $nullableDate,
        ];
    }

    public function attributes(): array
    {
        return [
            'sectionId'   => 'Section Id',
            'tagsId'      => 'Tag Id',
            'description' => 'Description',
            'limitDate'   => 'Limit Date',
            'responsible' => 'Responsible',
        ];
    }
}
