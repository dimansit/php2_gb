<?php


namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;

class SqliteLikesRepository implements LikesRepositoryInterface
{

    public function __construct(
        private PDO $connection
    )
    {
    }

    /**
     * @param User $user
     * @param Post $post
     * @return array
     */
    public function findLikePostByUser(User $user, Post $post): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes
                   WHERE
                         user_uuid = :user_uuid and
                         post_uuid = :post_uuid'
        );

        $statement->execute([
            ':user_uuid' => $user->getUuid(),
            ':post_uuid' => $post->getUuid(),
        ]);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(Like $like): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO likes (
                   uuid, 
                   user_uuid, 
                   post_uuid
                   )
                   VALUES (
                           :uuid, 
                           :user_uuid,
                           :post_uuid
                           )'
        );

        $statement->execute([
            ':uuid' => $like->getUuid(),
            ':user_uuid' => $like->getUser()->getUuid(),
            ':post_uuid' => $like->getPost()->getUuid()
        ]);

    }

    /**
     * @param UUID $uuid
     * @return array
     * @throws LikeNotFoundException
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     */
    public function getByLikesUuid(UUID $uuid): array
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM likes WHERE post_uuid = :post_uuid'
        );

        $statement->execute([
            ':post_uuid' => $uuid,
        ]);

        return $this->getLikes($statement, $uuid);
    }


    /**
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     * @throws LikeNotFoundException
     */
    public function getLikes(\PDOStatement $statement, $likeInfo): array
    {
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if (!$result) {
            throw new LikeNotFoundException(
                "Cannot find: $likeInfo"
            );
        }
        return $result;
    }


}