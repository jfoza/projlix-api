<?php

namespace App\Features\Base\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

abstract class FormRequest extends LaravelFormRequest
{
    const PAGE         = 'page';
    const PER_PAGE     = 'perPage';
    const COLUMN_ORDER = 'columnOrder';
    const COLUMN_NAME  = 'columnName';

    public array $sorting = [];

    public bool $requiredPagination = false;

    private ?string $columnsNameRules;

    abstract public function rules(): array;

    public function authorize(): true
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(
                ['errors' => $errors],
                Response::HTTP_UNPROCESSABLE_ENTITY
            )
        );
    }

    public function mergePaginationOrderRules(array $filters = []): array
    {
        $this->setColumnsNameRules();

        $paginationRules  = $this->requiredPagination ? 'required|integer' : 'nullable|integer';

        $columnOrderRules = 'nullable|string|in:asc,desc';

        $paginationOrderRules = [
            self::PAGE         => $paginationRules,
            self::PER_PAGE     => $paginationRules,
            self::COLUMN_ORDER => $columnOrderRules,
            self::COLUMN_NAME  => $this->getColumnsNameRules(),
        ];

        return array_merge($paginationOrderRules, $filters);
    }

    public function mergePaginationOrderAttributes(array $attributes = []): array
    {
        $paginationOrderAttributes = [
            self::PAGE         => 'Page',
            self::PER_PAGE     => 'Per Page',
            self::COLUMN_ORDER => 'Column Order',
            self::COLUMN_NAME  => 'Column Name',
        ];

        return array_merge($paginationOrderAttributes, $attributes);
    }

    private function setColumnsNameRules(): void
    {
        $this->columnsNameRules = 'nullable|string';

        if(count($this->sorting) > 0)
        {
            $allowedColumns = implode(',', $this->sorting);

            $this->columnsNameRules = "nullable|string|in:$allowedColumns";
        }
    }

    private function getColumnsNameRules(): string
    {
        return $this->columnsNameRules;
    }
}

