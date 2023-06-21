<?php

declare(strict_types=1);

namespace Shops\Contracts;

use Shops\Contracts\DataTransferObjects\ShopDto;
use App\Contracts\DataTransferObjects\PaginatedListDto;

interface ShopServiceContract
{
    /**
     * Получить магазин по ID
     *
     * @param int $id
     *
     * @return ShopDto
     *
     * @throws \App\Exceptions\EntityValidationException
     */
    public function getById(int $id): ShopDto;

    /**
     * Получить список магазинов
     *
     * @return PaginatedListDto
     */
    public function list(): PaginatedListDto;

    /**
     * Создать магазин
     *
     * @param string $title
     * @param string $url
     *
     * @return ShopDto
     *
     * @throws \App\Exceptions\EntityValidationException
     * @throws \App\Exceptions\EntityNotCreatedException
     */
    public function create(string $title, string $url): ShopDto;

    /**
     * Отредактировать магазин
     *
     * @param int $id
     * @param ?string $title
     * @param ?string $url
     *
     * @return ShopDto
     *
     * @throws \App\Exceptions\EntityNotFoundException
     * @throws \App\Exceptions\EntityValidationException
     * @throws \App\Exceptions\EntityNotUpdatedException
     */
    public function update(int $id, ?string $title, ?string $url): ShopDto;

    /**
     * Удалить магазин
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
