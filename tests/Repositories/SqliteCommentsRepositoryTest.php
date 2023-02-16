<?php


namespace GeekBrains\Blog\UnitTests\Repositories\UsersRepository;

use GeekBrains\Blog\UnitTests\DummyLogger;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class SqliteCommentsRepositoryTest extends TestCase
{

    /**
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     */
    public function testItThrowsAnExceptionWhenCommentNotFound(): void
    {

        $connectionStub = $this->createStub(PDO::class);
        $statementStub = $this->createStub(PDOStatement::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementStub->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub, new DummyLogger());

        $this->expectException(CommentNotFoundException::class);
        $this->expectExceptionMessage('Cannot find: 123e4567-e89b-12d3-a456-426614174000');

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

    public function testItSaveCommentToDatabase(): void
    {

        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);

        $statementMock
            ->expects($this->once()) // Ожидаем, что будет вызван один раз
            ->method('execute') // метод execute
            ->with([ // с единственным аргументом - массивом
                ':uuid' => '789351ac-e010-4c05-b271-7c6793b8eebf',
                ':username_uuid' => 'e69b6468-2c88-40a0-a8ed-331fba8037e0',
                ':post_uuid' => 'db42948e-832b-418c-ac5d-1a79971e8294',
                ':text' => 'comment',
            ]);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub, new DummyLogger());

        $repository->save(
            new Comment( // Свойства пользователя точно такие,
                new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
                new User(
                    new UUID('e69b6468-2c88-40a0-a8ed-331fba8037e0'),
                    'useranme',
                    'pass',
                    new Name('ivan', 'ivanov')
                ),
                new Post(
                    new UUID('db42948e-832b-418c-ac5d-1a79971e8294'),
                    new User(
                        new UUID('e44e3d3e-a65d-4d7c-aed7-aedb25764591'),
                        'useranme',
                        'pass',
                        new Name('ivan', 'ivanov')
                    ),
                    'title',
                    'text'
                ),
                'comment'
            )
        );


    }



}
