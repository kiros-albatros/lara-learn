<?php

namespace App\Helpers;

use App\Contracts\DataTransferObjects\PaginatedListDto;

trait DomainModelController
{
    protected function outputPaginatedList(PaginatedListDto $list, ?callable $fn = null): array
    {
        $result = collect($list)->toArray();
        if ($fn !== null) {
            $result['data'] = array_map($fn, $result['data']);
        }

        return $result;
    }
}
