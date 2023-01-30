<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\UsersRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

use PDO;


class SqliteUsersRepository implements UsersRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(User $user): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO users (uuid, first_name, last_name, username)
                   VALUES (
                           :uuid, 
                           :first_name,
                           :last_name, 
                           :username
                           )'
        );

        $statement->execute([
            ':uuid' => $user->getUuid(),
            ':first_name' => $user->getName()->first(),
            ':last_name' => $user->getName()->last(),
            ':username' => $user->getUsername()
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function get(UUID $uuid): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE uuid = :uuid'
        );
        $statement->execute([
            ':uuid' => $uuid,
        ]);
        return $this->getUser($statement, $uuid);
    }


    /**
     * @throws UserNotFoundException
     */
    public function getByUsername(string $username): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users WHERE username = :username'
        );
        $statement->execute([
            ':username' => $username,
        ]);
        return $this->getUser($statement, $username);
    }


    /**
     * @throws UserNotFoundException
     */
    public function getRandomUser(): User
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM users ORDER BY RANDOM() LIMIT 1'
        );
        $statement->execute([]);
        return $this->getUser($statement, 'Random User');
    }

    //PDOStateme $statement - убрал потому как не смог разобраться почему в ошибку уходил

    /**
     * @throws UserNotFoundException
     */
    private function getUser($statement, string $username): User
    {

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        print_r($result);
        echo '----------------------------------';
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find user: $username"
            );
        }

        return new User(
            new UUID($result['uuid']),
            $result['username'],
            new Name($result['first_name'], $result['last_name'])
        );
    }


}
