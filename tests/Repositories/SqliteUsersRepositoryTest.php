<?php


namespace GeekBrains\Blog\UnitTests\Repositories\UsersRepository;

use GeekBrains\Blog\UnitTests\DummyLogger;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteUsersRepositoryTest extends TestCase
{

    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {

        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('Cannot find user: larionova.donat1');

        $repository->getByUsername('larionova.donat1');
    }

    public function testItGetInDatabase()
    {

        $statementStub = $this->createStub(PDOStatement::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $connectionStub = $this->createStub(PDO::class);
        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementMock);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot find user: 123e4567-e89b-12d3-a456-426614174000');
        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    public function testItGetByUserNameInDatabase()
    {

        $statementStub = $this->createStub(PDOStatement::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $connectionStub = $this->createStub(PDO::class);
        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementMock);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('Cannot find user: Ivan');

        $repository->getByUsername('Ivan');
    }

    /**
     * Поверка метода getRandomUser
     * @throws UserNotFoundException
     */
    public function testItGetRandomUserInDatabase()
    {

        $statementStub = $this->createStub(PDOStatement::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $connectionStub = $this->createStub(PDO::class);

        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());

        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $this->expectException(UserNotFoundException::class);

        $this->expectExceptionMessage('Cannot find user: Random User');

        $repository->getRandomUser();
    }

    public function testItSavesUserToDatabase(): void
    {

        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once()) // Ожидаем, что будет вызван один раз
            ->method('execute') // метод execute
            ->with([ // с единственным аргументом - массивом
                ':uuid' => 'b1f842f4-c8f3-40a0-8c5e-127661dddecc',
                ':username' => 'user10021',
                ':first_name' => 'test',
                ':password' => '1458029e2a4e21c21ed1f51883627278151bf00d29c272430a9e3c6a3dc1cdf5',
                ':last_name' => 'test2',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteUsersRepository($connectionStub, new DummyLogger());

        $repository->save(
            new User( // Свойства пользователя точно такие,
                new UUID('b1f842f4-c8f3-40a0-8c5e-127661dddecc'),
                'user10021',
                '1458029e2a4e21c21ed1f51883627278151bf00d29c272430a9e3c6a3dc1cdf5',
                new Name('test', 'test2')
            )
        );

    }
}
