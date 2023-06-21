<?php

namespace Accounts\Contracts\DataTransferObjects;

class UserDto
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $phone = null,
        public readonly ?bool $is_blocked = null,
        public readonly ?string $role = null,
        public readonly ?string $signature = null,
        public readonly ?string $birthday = null,
        public readonly ?string $created_at = null,
        public readonly ?string $updated_at = null
    ) {
    }
}
