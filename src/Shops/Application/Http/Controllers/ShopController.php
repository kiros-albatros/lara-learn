<?php

namespace Shops\Application\Http\Controllers;

use Shops\Contracts\DataTransferObjects\ShopDto;
use Shops\Domain\Models\Shop;
use Shops\Domain\Services\ShopService;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotDeletedException;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityNotUpdatedException;
use App\Exceptions\EntityValidationException;
use App\Helpers\DomainModelController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ShopController extends Controller
{
    use DomainModelController;

    public function __construct(
        private ShopService $shopService
    ) {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if($request->user()->cannot('viewAny', Shop::class), 403);
        $paginatedShops = $this->shopService->list(
            searchQuery: $request->get('q'),
        );

        return Inertia::render('Shops/Index', [
            'shops' => $this->outputPaginatedList($paginatedShops, fn(ShopDto $shop) => [
                'id' => $shop->id,
                'title' => $shop->title,
                'url' => $shop->url,
                'created_at' => $shop->created_at,
            ]),
           // 'initialFilter' => $request->only(['role', 'q']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if($request->user()->cannot('create', Shop::class), 403);
        return Inertia::render('Shops/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if($request->user()->cannot('create', Shop::class), 403);
        try {
            $this->shopService->create(
                title: $request->string('title'),
                url: $request->string('url'),
            );
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotCreatedException $exception) {
            return back()->withErrors(['name' => 'Не удается создать магазин: ' . $exception->getMessage()]);
        }

        return Redirect::route('shops.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $shop)
    {
        abort_if($request->user()->cannot('view', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Shops/Show', [
            'shop' => (array)$shopDto,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('update', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Shops/Edit', [
            'id' => $shopDto->id,
            'values' => $shopDto,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('update', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);
            $this->shopService->update(
                id: $shopDto->id,
                title: $request->stringOrNull('title'),
                url: $request->stringOrNull('url'),
            );
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotUpdatedException $exception) {
            return back()->withErrors(['title' => 'Не удается отредактировать магазин: ' . $exception->message]);
        }

        return Redirect::route('shops.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, int $shop)
    {
        abort_if($request->user()->cannot('delete', Shop::class), 403);
        try {
            $shopDto = $this->shopService->getById($shop);
            $this->shopService->delete($shopDto->id);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityNotDeletedException $exception) {
            return back()->withErrors(['id' => 'Не удается удалить магазин: ' . $exception->message]);
        }

        return Redirect::back(303);
    }
}
