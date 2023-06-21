<?php

namespace Accounts\Application\Http\Controllers;

use Accounts\Contracts\DataTransferObjects\UserDto;
use Accounts\Domain\Models\User;
use Accounts\Domain\Services\UserService;
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

class UserController extends Controller
{
    use DomainModelController;

    public function __construct(
        private UserService $userService
    ) {
    }

    /**
     * Список пользователей
     * GET /users
     */
    public function index(Request $request)
    {
        abort_if($request->user()->cannot('viewAny', User::class), 403);
        $paginatedUsers = $this->userService->list(
            role: $request->get('role'),
            searchQuery: $request->get('q'),
        );

        $roleTitles = config('models.users.roles', []);

        return Inertia::render('Users/Index', [
            'users' => $this->outputPaginatedList($paginatedUsers, fn(UserDto $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'is_blocked' => $user->is_blocked,
                'role' => $user->role,
                'role_title' => data_get($roleTitles, $user->role, $user->role),
                'created_at' => $user->created_at,
            ]),
            'initialFilter' => $request->only(['role', 'q']),
        ]);
    }

    /**
     * Форма создания пользователя
     * GET /users/create
     */
    public function create(Request $request)
    {
        abort_if($request->user()->cannot('create', User::class), 403);

        return Inertia::render('Users/Create', [
            'userRoles' => config('models.users.roles', ['admin' => 'Админ']),
            'teamRoles' => config('models.users.team_roles', ['admin']),
        ]);
    }

    /**
     * Сохранение созданного пользователя
     * POST /users
     */
    public function store(Request $request)
    {
        abort_if($request->user()->cannot('create', User::class), 403);
        try {
            $this->userService->create(
                name: $request->string('name'),
                email: $request->string('email'),
                password: $request->string('password'),
                phone: $request->stringOrNull('phone'),
                role: $request->stringOrNull('role'),
                signature: $request->stringOrNull('signature'),
                birthday: $request->stringOrNull('birthday')
            );
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotCreatedException $exception) {
            // Пока что просто выводим ошибку под любым полем, чтобы было видно
            // TODO В будущем нужно логировать такие ошибки, и выводить системную ошибку.
            return back()->withErrors(['name' => 'Не удается создать пользователя: ' . $exception->getMessage()]);
        }

        return Redirect::route('users.index');
    }

    /**
     * Страница пользователя
     * GET /users/{id}
     */
    public function show(Request $request, int $user)
    {
        try {
            $userDto = $this->userService->getById($user);
            abort_if($request->user()->cannot('view', $userDto), 403);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Users/Show', [
            'user' => array_merge((array)$userDto, [
                'role_title' => config('models.users.roles.' . $userDto->role, $userDto->role),
            ]),
        ]);
    }

    /**
     * Форма редактирования пользователя
     * GET /users/{id}/edit
     */
    public function edit(Request $request, int $user)
    {
        try {
            $userDto = $this->userService->getById($user);
            abort_if($request->user()->cannot('update', $userDto), 403);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        }

        return Inertia::render('Users/Edit', [
            'id' => $userDto->id,
            'values' => $userDto,
            'userRoles' => config('models.users.roles', ['admin' => 'Админ']),
            'teamRoles' => config('models.users.team_roles', ['admin']),
        ]);
    }

    /**
     * Сохранение редактируемого пользователя
     * PUT /users/{id}
     */
    public function update(Request $request, int $user)
    {
        try {
            $userDto = $this->userService->getById($user);
            abort_if($request->user()->cannot('update', $userDto), 403);
            $this->userService->update(
                id: $userDto->id,
                name: $request->stringOrNull('name'),
                email: $request->stringOrNull('email'),
                // Если пустая строка, то не обновляем
                password: $request->post('password') ? $request->stringOrNull('password') : null,
                phone: $request->stringOrNull('phone'),
                role: $request->stringOrNull('role'),
                signature: $request->stringOrNull('signature'),
                birthday: $request->stringOrNull('birthday')
            );
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityValidationException $exception) {
            return back()->withErrors($exception->messages);
        } catch (EntityNotUpdatedException $exception) {
            // Пока что просто выводим ошибку под любым полем, чтобы было видно
            // TODO В будущем нужно логировать такие ошибки, и выводить системную ошибку.
            return back()->withErrors(['email' => 'Не удается отредактировать пользователя: ' . $exception->message]);
        }

        return Redirect::route('users.index');
    }

    /**
     * Удаление пользователя
     * DELETE /users/{id}
     */
    public function destroy(Request $request, int $user)
    {
        try {
            $userDto = $this->userService->getById($user);
            abort_if($request->user()->cannot('delete', $userDto), 403);
            $this->userService->delete($userDto->id);
        } catch (EntityNotFoundException $exception) {
            abort(Response::HTTP_NOT_FOUND);
        } catch (EntityNotDeletedException $exception) {
            // TODO В будущем нужно логировать такие ошибки, и выводить системную ошибку.
            return back()->withErrors(['id' => 'Не удается удалить пользователя: ' . $exception->message]);
        }

        return Redirect::back(303);
    }
}
