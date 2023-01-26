<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\User;
use PDO;

class SqliteUsersRepository
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(User $user): void
    {
        echo $user->getUuid().PHP_EOL;
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, first_name, last_name, username)
VALUES (:uuid, :first_name, :last_name, :username)'
        );
// Выполняем запрос с конкретными значениями
        $statement->execute([
            ':uuid' => $user->getUuid(),
            ':first_name' => $user->getName()->first(),
            ':last_name' => $user->getName()->last(),
            ':username' => $user->getUsername()
        ]);
    }
}
