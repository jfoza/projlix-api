<?php
declare(strict_types=1);

namespace App\Features\Project\Cards\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Shared\Rules\Uuid4Rule;

class CardsFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        $nullableUuid4     = ['nullable', 'string', new Uuid4Rule()];
        $nullableString    = ['nullable', 'string'];
        $nullableDate      = ['nullable', 'date'];
        $nullableArray     = ['nullable', 'array'];

        return [
            'projectsId'    => $nullableArray,
            'tagsId'        => $nullableArray,
            'code'          => $nullableString,
            'sectionId'     => $nullableUuid4,
            'initialDate'   => $nullableDate,
            'finalDate'     => $nullableDate,
            'responsibleId' => $nullableUuid4,

            'projectsId.*'  => $nullableUuid4,
            'tagsId.*'      => $nullableUuid4,
        ];
    }

    public function attributes(): array
    {
        return [
            'projectsId'    => 'Projects Id',
            'code'          => 'Code',
            'sectionId'     => 'Section Id',
            'initialDate'   => 'Initial Date',
            'finalDate'     => 'Final Date',
            'responsibleId' => 'Responsible Id',
            'tagsId'        => 'Tags Id',
        ];
    }
}
