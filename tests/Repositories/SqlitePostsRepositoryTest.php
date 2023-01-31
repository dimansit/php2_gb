<?php


namespace GeekBrains\Blog\UnitTests\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqlitePostsRepositoryTest extends TestCase
{

    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {

        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $this->expectException(PostNotFoundException::class);

        $this->expectExceptionMessage('Cannot find: 123e4567-e89b-12d3-a456-426614174000');

        $repository->get(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
        );
    }


    /**
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     */
    public function testItSavesPostToDatabase(): void
    {

        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once()) // Ожидаем, что будет вызван один раз
            ->method('execute') // метод execute
            ->with([ // с единственным аргументом - массивом
                ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
                ':username_uuid' => 'e44e3d3e-a65d-4d7c-aed7-aedb25764591',
                ':title' => 'title',
                ':text' => 'text',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqlitePostsRepository($connectionStub);

        $repository->save(
            new Post(
                new UUID('123e4567-e89b-12d3-a456-426614174000'),
                new User(
                    new UUID('e44e3d3e-a65d-4d7c-aed7-aedb25764591'),
                    'useranme',
                    new Name('ivan', 'ivanov')
                ),
                'title',
                'text'
            )
        );

    }
}
