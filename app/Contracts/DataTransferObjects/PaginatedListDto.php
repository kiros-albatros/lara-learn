<?php

namespace App\Contracts\DataTransferObjects;

use App\Helpers\DomainModel;

class PaginatedListDto
{
    public function __construct(
        /** @var DomainModel[] $data */
        public readonly array $data,
        public readonly int $currentPage,
        public readonly int $lastPage,
        public readonly int $perPage,
        public readonly int $total,
        /** @var array[] $links */
        public readonly array $links = [],
    ) {
    }
}
