<?php

namespace Accounts\Domain\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Accounts\Contracts\DataTransferObjects\UserDto;
use App\Casts\Hash;
use App\Casts\Json;
use App\Casts\PhoneNumber;
use App\Helpers\DomainModel;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Laravel\Sanctum\HasApiTokens;

class User extends DomainModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use \Illuminate\Auth\Authenticatable;
    use Authorizable;
    use CanResetPassword;
    use MustVerifyEmail;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public function fillableRules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:' . $this->getTable()],
            'password' => ['required'],
            'phone' => ['min:11', 'max:17', 'unique:' . $this->getTable()],
            'is_blocked' => ['boolean'],
            'role' => [
                Rule::in(array_keys(config('models.users.roles', ['admin' => 'Админ']))),
            ],
            'meta->signature' => ['max:200'],
            'birthday' => ['date'],
        ];
    }

    public function toDto(): mixed
    {
        return new UserDto(
            id: $this->id,
            name: $this->name,
            email: $this->email,
            phone: $this->phone,
            is_blocked: $this->is_blocked,
            role: $this->role,
            signature: data_get($this->meta, 'signature'),
            birthday: data_get($this->meta, 'birthday'),
            created_at: $this->created_at,
            updated_at: $this->updated_at
        );
    }

    protected $guarded = [
        'id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => Hash::class,
        'phone' => PhoneNumber::class,
        'email_verified_at' => 'datetime',
        'meta' => Json::class,
    ];

    public function scopeMaybeFilterRole($query, ?string $role)
    {
        if ($role) {
            $query->where('role', '=', $role);
        }
    }

    public function scopeMaybeSearch($query, ?string $q)
    {
        if ($q !== null and $q !== '') {
            if (str_contains($q, '@')) {
                $query->where('email', 'like', "%{$q}%");
            } elseif (preg_match('~^[0-9\.\-\+\ ]+$~', $q)) {
                $phone_value = preg_replace('~[^0-9\.\-\+\ ]+~', '', $q);
                $query->where('phone', 'like', "{$phone_value}%");
            } else {
                $query->where('name', 'like', "%{$q}%");
            }
        }
    }

    /**
     * Сортируем по уровню доступа роли
     */
    public function scopeOrderByRole($query, $direction = 'desc')
    {
        $roles = array_keys(config('models.users.roles', ['admin' => '']));
        $whenThens = collect($roles)->map(fn($_, $index) => 'when ("role" = ?) then ' . $index)->implode(' ');
        $query->orderByRaw(
            'case ' . $whenThens . ' end desc',
            // Если нужно поменять порядок сортировки, просто переворачиваем массив в подстановках case-значений
            ($direction === 'desc') ? $roles : array_reverse($roles)
        );
    }
}
