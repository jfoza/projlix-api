<?php
declare(strict_types=1);

namespace App\Features\Project\Sections\Requests;

use App\Features\Base\Requests\FormRequest;

class SectionReorderingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'newOrder' => ['required', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'newOrder' => 'New Order',
        ];
    }
}
