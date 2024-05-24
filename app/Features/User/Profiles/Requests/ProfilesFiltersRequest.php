<?php
declare(strict_types=1);

namespace App\Features\User\Profiles\Requests;

use App\Features\Base\Requests\FormRequest;
use App\Features\User\Profiles\Enums\ProfileTypesEnum;

class ProfilesFiltersRequest extends FormRequest
{
    public function rules(): array
    {
        $profileTypes = implode(',', array_column(ProfileTypesEnum::cases(), 'value'));

        return [
            'profileType' => "nullable|string|in:$profileTypes",
        ];
    }
}
