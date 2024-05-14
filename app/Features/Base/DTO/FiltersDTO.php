<?php

namespace App\Features\Base\DTO;

use App\Features\Base\Pagination\PaginationOrder;

class FiltersDTO
{
    public function __construct(
        public PaginationOrder $paginationOrder,
    ) {}
}
