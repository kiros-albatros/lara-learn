<?php

namespace Accounts\Domain\Services;

use Accounts\Contracts\DataTransferObjects\UserDto;
use Accounts\Contracts\UserServiceContract;
use Accounts\Domain\Models\User;
use App\Casts\PhoneNumber;
use App\Exceptions\EntityNotDeletedException;
use App\Helpers\DomainModelService;
use App\Exceptions\EntityNotFoundException;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityNotUpdatedException;
use Illuminate\Database\QueryException;
use App\Contracts\DataTransferObjects\PaginatedListDto;

class UserService extends DomainModelService implements UserServiceContract
{
    /**
     * @inheritDoc
     */
    public function getById(int $id): UserDto
    {
        $user = User::find($id);
        if (! $user) {
            throw new EntityNotFoundException();
        }

        return $user->toDto();
    }

    /**
     * @inheritDoc
     */
    public function list(
        ?string $role = null,
        ?string $searchQuery = null,
        int $perPage = 25,
        array $linksQueryString = []
    ): PaginatedListDto {
        $users = User::query()
            ->maybeFilterRole($role)
            ->maybeSearch($searchQuery)
            ->orderByRole('desc');

        return $this->toPaginatedListDto($users, $perPage, $linksQueryString);
    }

    /**
     * @inheritDoc
     */
    public function getTeamUserNames(): array
    {
        return User::query()
            ->whereIn('role', config('models.users.team_roles', ['admin']))
            ->pluck('name', 'id')
            ->toArray();
    }

    /**
     * @inheritDoc
     */
    public function create(
        string $name,
        string $email,
        string $password,
        ?string $phone = null,
        ?string $role = null,
        ?string $signature = null,
        ?string $birthday = null
    ): UserDto {
        $user = new User();
        if ($phone !== null) {
            // Приводим телефонный номер к единому формату до валидации для проверки уникальности
            $phone = (new PhoneNumber())->set($user, 'phone', $phone, []);
        }
        $this->validateAndFill($user, [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'role' => $role,
            'signature' => $signature,
            'birthday' => $birthday,
        ]);
        try {
            $user->save();
        } catch (QueryException $exception) {
            throw new EntityNotCreatedException($exception->getMessage());
        }

        return $user->toDto();
    }

    /**
     * @inheritDoc
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
    ): UserDto {
        $user = User::find($id);
        if (! $user) {
            throw new EntityNotFoundException();
        }
        if ($phone !== null) {
            // Приводим телефонный номер к единому формату до валидации для проверки уникальности
            $phone = (new PhoneNumber())->set($user, 'phone', $phone, []);
        }
        $this->validateAndFill($user, [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'phone' => $phone,
            'is_blocked' => $is_blocked,
            'role' => $role,
            'signature' => $signature,
            'birthday' => $birthday,
        ]);
        try {
            $user->save();
        } catch (QueryException $exception) {
            throw new EntityNotUpdatedException($exception->getMessage());
        }

        return $user->toDto();
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): void
    {
        $user = User::find($id);
        if (! $user) {
            throw new EntityNotFoundException();
        }

        $user->delete();
    }
}
