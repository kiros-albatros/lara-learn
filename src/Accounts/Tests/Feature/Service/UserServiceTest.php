<?php

namespace Accounts\Tests\Feature\Service;

use Accounts\Contracts\DataTransferObjects\UserDto;
use Accounts\Domain\Models\User;
use Accounts\Domain\Services\UserService;
use App\Contracts\DataTransferObjects\PaginatedListDto;
use App\Exceptions\EntityNotCreatedException;
use App\Exceptions\EntityValidationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var ?UserService
     */
    protected ?UserService $userService = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = new UserService();
    }

    /** @test
     * @throws EntityNotCreatedException|EntityValidationException
     */
    public function inCanCreateUsers()
    {
        // Создаем пользователя сервисом
        try {
            $userDto = $this->userService->create(
                name: 'Сергей Тестов',
                email: 'serg@example.com',
                password: 'test',
                phone: '+79261234567',
                role: 'admin'
            );
        } catch (EntityNotCreatedException $e) {
            throw new EntityNotCreatedException($e->getMessage());
        }

        // Проверяем полученные данные
        $this->assertInstanceOf(UserDto::class, $userDto);
        $this->assertEquals('serg@example.com', $userDto->email ?? '');

        // Проверяем, что создалось в БД
        $this->assertDatabaseHas('users', ['id' => $userDto->id ?? 0, 'email' => 'serg@example.com']);
    }

    /** @test */
    public function itDoesntAllowToCreateDuplicates()
    {
        // Создаем пользователя
        try {
            $this->userService->create(
                name: 'Сергей Тестов',
                email: 'serg@example.com',
                password: 'test',
                phone: '+79261234567'
            );
        } catch (EntityNotCreatedException $e) {
            throw new EntityNotCreatedException($e->getMessage());
        }

        // Не позволяет добавить пользователя с таким же email адресом
        try {
            $this->userService->create(
                name: 'Сергей',
                email: 'serg@example.com',
                password: 'test'
            );
            $this->fail('Валидация email не была выполнена');
        } catch (EntityValidationException $exception) {
            $this->assertStringContainsString('email', $exception->getMessage());
        }

        // Не позволяет добавить пользователя с таким же телефоном, пусть и в другом формате
        try {
            $this->userService->create(
                name: 'Сергей',
                email: 'serg2@example.com',
                password: 'test',
                phone: '8-926-123-45-67'
            );
            $this->fail('Валидация phone не была выполнена');
        } catch (EntityValidationException $exception) {
            $this->assertArrayHasKey('phone', $exception->messages);
        }
    }

    /** @test */
    public function itCanGetUsersById()
    {
        // Создаем пользователя моделью
        $user = User::create(['name' => 'Сергей', 'email' => 'serg@example.com', 'password' => 'test']);

        // Получаем пользователя по ID
        $userDto = $this->userService->getById($user->id);

        // Проверяем, что получили правильные данные
        $this->assertInstanceOf(UserDto::class, $userDto);
        $this->assertEquals('serg@example.com', $userDto->email);
    }

    /** @test */
    public function itCanListUsers()
    {
        // Создаем троих пользователей
        $user1 = User::create(['name' => 'Сергей 1', 'email' => 'serg@example.com', 'password' => 't']);
        $user2 = User::create(['name' => 'Сергей 2', 'email' => 'serg2@example.com', 'password' => 't']);
        $user3 = User::create(['name' => 'Сергей 3', 'email' => 'serg3@example.com', 'password' => 't']);

        // Получаем список пользователей
        $list = $this->userService->list();

        // Убеждаемся, что данные получены в правильном формате
        $this->assertInstanceOf(PaginatedListDto::class, $list);
        $this->assertCount(3, $list->data);
        $this->assertInstanceOf(UserDto::class, $list->data[0]);
        $this->assertEquals('serg@example.com', $list->data[0]->email);
    }

    /** @test */
    public function itCanGetUserTeamNames()
    {
        // Создаем админа
        $user = User::create(['name' => 'Сергей', 'email' => 'serg@example.com', 'password' => 't', 'role' => 'admin']);

        // Получаем пользователей команды
        $teamUserNames = $this->userService->getTeamUserNames();

        // Убеждаемся, что созданный пользователь есть там
        $this->assertEquals([$user->id => $user->name], $teamUserNames);
    }

    /** @test */
    public function itCanUpdateUsers()
    {
        // Создаем пользователя моделью
        $user = User::create(['name' => 'Сергей', 'email' => 'serg@example.com', 'password' => 'test']);

        // Редактируем
        $userDto = $this->userService->update(
            id: $user->id,
            email: 'serg2@example.com'
        );

        // Убеждаемся, что данные получены в правильном формате
        $this->assertInstanceOf(UserDto::class, $userDto);
        $this->assertEquals('serg2@example.com', $userDto->email);

        // Убеждаемся, что изменилось в БД
        $user->refresh();
        $this->assertEquals('serg2@example.com', $user->email);
    }

    /** @test */
    public function itProperlyHashesPasswords()
    {
        // Добавляем пользователя сервисом
        $userDto = $this->userService->create(name: 'Сергей', email: 's@example.com', password: 'test');

        // Убеждаемся, что в БД правильный хеш
        $user = User::find($userDto->id);
        $this->assertTrue(Hash::check('test', $user->password));

        // Меняем пароль сервисом
        $this->userService->update(id: $userDto->id, password: 'test2');

        // Убеждаемся, что в БД правильный хеш
        $user->refresh();
        $this->assertTrue(Hash::check('test2', $user->password));
    }

    /** @test */
    public function itCanDeleteUsers()
    {
        // Создаем пользователя моделью
        $user = User::create(['name' => 'Сергей', 'email' => 'serg@example.com', 'password' => 'test']);

        // Удаляем сервисом
        $this->userService->delete($user->id);

        // Убеждаемся, что удалилось из БД
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
