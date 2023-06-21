<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class NestedSetTree
{
    /**
     * @param Collection|array $tree
     * @param ?callable $fn
     *
     * @return array
     */
    public static function traverseTree(Collection|array $tree, ?callable $fn = null): array
    {
        $tree = is_array($tree) ? collect($tree) : $tree;
        $fn = $fn ?: fn($item) => $item;
        $resultTree = [];
        foreach ($tree as $item) {
            if ($item instanceof Model) {
                $resultItem = $fn($item->setHidden(['_lft', '_rgt'])->toArray());
                $resultItem['children'] = self::traverseTree($item->children, $fn);
            } else {
                $resultItem = $fn($item);
                $resultItem['children'] = self::traverseTree(data_get($item, 'children', []), $fn);
            }

            $resultTree[] = $resultItem;
        }
        return $resultTree;
    }
}
