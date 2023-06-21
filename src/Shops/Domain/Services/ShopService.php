<?php

namespace Shops\Domain\Services;

use Shops\Contracts\DataTransferObjects\ShopDto;
use Shops\Contracts\ShopServiceContract;
use Shops\Domain\Models\Shop;
use App\Helpers\DomainModelService;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotUpdatedException;
use Illuminate\Database\QueryException;
use App\Contracts\DataTransferObjects\PaginatedListDto;

class ShopService extends DomainModelService implements ShopServiceContract
{
    /**
     * @inheritDoc
     */
    public function getById(int $id): ShopDto
    {
        $shop = Shop::find($id);
        if (! $shop) {
            throw new EntityNotFoundException();
        }

        return $shop->toDto();
    }

    /**
     * @inheritDoc
     */
    public function list(
        ?string $searchQuery = null,
        int $perPage = 25,
        array $linksQueryString = []
    ): PaginatedListDto {
        $shops = Shop::query()
            ->orderBy('id', 'desc')
            ->maybeSearch($searchQuery);

        return $this->toPaginatedListDto($shops, $perPage, $linksQueryString);
    }

    /**
     * @inheritDoc
     */
    public function create(
        string $title,
        string $url,
    ): ShopDto {
        $shop = new Shop();
        $this->validateAndFill($shop, [
            'title' => $title,
            'url' => $url,
        ]);
        try {
            $shop->save();
        } catch (QueryException $exception) {
            throw new EntityNotCreatedException($exception->getMessage());
        }

        return $shop->toDto();
    }

    /**
     * @inheritDoc
     */
    public function update(
        int $id,
        ?string $title = null,
        ?string $url = null,
    ): ShopDto {
        $shop = Shop::find($id);
        if (! $shop) {
            throw new EntityNotFoundException();
        }
        $this->validateAndFill($shop, [
            'title' => $title,
            'url' => $url,
        ]);
        try {
            $shop->save();
        } catch (QueryException $exception) {
            throw new EntityNotUpdatedException($exception->getMessage());
        }

        return $shop->toDto();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $shop = Shop::find($id);
        if (! $shop) {
            throw new EntityNotFoundException();
        }

        $shop->delete();
    }
}
