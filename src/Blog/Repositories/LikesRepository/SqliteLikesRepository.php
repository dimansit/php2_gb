<?php


namespace GeekBrains\LevelTwo\Blog\Repositories\LikesRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;

class SqliteLikesRepository implements LikesRepositoryInterface
{

    public function __construct(
        private PDO $connection
    )
    {
    }

//    private function likeExistByUser(Like $like)
//    {
//        $statement = $this->connection->prepare(
//            'SELECT * FROM like
//                   WHERE
//                         user_uuid = :user_uuid and
//                         post_uuid = :post_uuid'
//        );
//
//        $statement->execute([
//            ':user_uuid' => $like->getPostUuid(),
//            ':user_uuid' => $like->getUserUuid(),
//        ]);
//
//        return $this->getLike($statement, 'UserLike');
//    }

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


    public function getByPostUuid(UUID $uuid)
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
    public function getLikes(\PDOStatement $statement, $likeInfo)
    {
        $result = $statement->fetchAll();
        if (!$result) {
            throw new LikeNotFoundException(
                "Cannot find: $likeInfo"
            );
        }

//        $user = new SqliteUsersRepository($this->connection);
//        $post = new SqlitePostsRepository($this->connection);
//
//        $likes = [];

        return $result;
    }

    public function getByLikesUuid(UUID $uuid): Like
    {
        // TODO: Implement getByLikesUuid() method.
    }
}