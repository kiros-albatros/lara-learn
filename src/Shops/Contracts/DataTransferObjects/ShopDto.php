<?php

namespace Shops\Contracts\DataTransferObjects;

class ShopDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $url,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null
    ) {
    }
}
