<?php


namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;


use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use Psr\Log\LoggerInterface;

class SqlitePostsRepository implements PostsRepositoryInterface
{

    public function __construct(
        private PDO $connection,
        private LoggerInterface $logger,
    )
    {
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (
                   uuid, 
                   username_uuid, 
                   title, 
                   text
                   )
                   VALUES (
                           :uuid, 
                           :username_uuid,
                           :title, 
                           :text
                           )'
        );

        $statement->execute([
            ':uuid' => $post->getUuid(),
            ':username_uuid' => $post->getUser()->getUuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText()
        ]);
        $this->logger->info('Post creat: ' . $post->getUuid());

    }

    public function deletePost(UUID $postUuId): bool
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts WHERE uuid = :uuid'
        );

        $result = $statement->execute([
            ':uuid' => $postUuId,
        ]);

        if (!$result) {
            $this->logger->warning("Cannot not delete, post not found: $postUuId");
            throw new PostNotFoundException(
                "Cannot not delete: $postUuId"
            );
        }
        return true;
    }

    public function get(UUID $uuid): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => $uuid,
        ]);

        return $this->getPost($statement, $uuid);
    }


    /**
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     */
    public function getPost(\PDOStatement $statement, string $postUuId): Post
    {
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$result) {
            $this->logger->warning("Cannot find: $postUuId");
            throw new PostNotFoundException(
                "Cannot find: $postUuId"
            );
        }
        $user = new SqliteUsersRepository($this->connection);
        return new Post(
            new UUID($result['uuid']),
            $user->get(new UUID($result['username_uuid'])),
            $result['title'],
            $result['text']
        );
    }
}