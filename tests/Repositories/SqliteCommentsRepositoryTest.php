<?php


namespace GeekBrains\Blog\UnitTests\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
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

    public function testItThrowsAnExceptionWhenUserNotFound(): void
    {

        $connectionStub = $this->createStub(PDO::class);

        $statementStub = $this->createStub(PDOStatement::class);

        $statementMock = $this->createMock(PDOStatement::class);

        $statementStub->method('fetch')->willReturn(false);

        $connectionStub->method('prepare')->willReturn($statementMock);

        $repository = new SqliteCommentsRepository($connectionStub);

        $this->expectException(CommentNotFoundException::class);

        $this->expectExceptionMessage('Cannot find user: 123e4567-e89b-12d3-a456-426614174000');

        $repository->get(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }


}
