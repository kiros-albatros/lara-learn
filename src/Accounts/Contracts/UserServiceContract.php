<?php

declare(strict_types=1);

namespace Accounts\Contracts;

use Accounts\Contracts\DataTransferObjects\UserDto;
use App\Contracts\DataTransferObjects\PaginatedListDto;

interface UserServiceContract
{
    /**
     * Получить пользователя по ID
     *
     * @param int $id
     *
     * @return UserDto
     *
     * @throws \App\Exceptions\EntityValidationException
     */
    public function getById(int $id): UserDto;

    /**
     * Получить список пользователей
     *
     * @param ?string $role
     * @param ?string $searchQuery
     * @param int $perPage
     * @param array $linksQueryString Дополнительные GET-параметры, которые нужно добавить к ссылкам
     *
     * @return PaginatedListDto
     */
    public function list(
        ?string $role = null,
        ?string $searchQuery = null,
        int $perPage = 25,
        array $linksQueryString = []
    ): PaginatedListDto;

    /**
     * Получить список членов команды в формате ID => Имя
     * @return array [id => name]
     */
    public function getTeamUserNames(): array;

    /**
     * Создать пользователя
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @param ?string $phone
     * @param ?string $role
     * @param ?string $signature
     * @param ?string $birthday
     *
     * @return UserDto
     *
     * @throws \App\Exceptions\EntityValidationException
     * @throws \App\Exceptions\EntityNotCreatedException
     */
    public function create(
        string $name,
        string $email,
        string $password,
        ?string $phone = null,
        ?string $role = null,
        ?string $signature = null,
        ?string $birthday = null
    ): UserDto;

    /**
     * Отредактировать пользователя
     *
     * @param int $id
     * @param ?string $name
     * @param ?string $email
     * @param ?string $password
     * @param ?string $phone
     * @param ?bool $is_blocked
     * @param ?string $role
     * @param ?string $signature
     * @param ?string $birthday
     *
     * @return UserDto
     *
     * @throws \App\Exceptions\EntityNotFoundException
     * @throws \App\Exceptions\EntityValidationException
     * @throws \App\Exceptions\EntityNotUpdatedException
     */
    public function update(
        int $id,
        ?string $name = null,
        ?string $email = null,
        ?string $password = null,
        ?string $phone = null,
        ?bool $is_blocked = null,
        ?string $role = null,
        ?string $signature = null,
        ?string $birthday = null
    ): UserDto;

    /**
     * Удалить пользователя
     *
     * @param int $id
     *
     * @return void
     *
     * @throws \App\Exceptions\EntityNotFoundException
     * @throws \App\Exceptions\EntityNotDeletedException
     */
    public function delete(int $id): void;
}
