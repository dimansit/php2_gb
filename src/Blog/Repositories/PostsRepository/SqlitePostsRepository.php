<?php


namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;


use GeekBrains\LevelTwo\Blog\Post;
use PDO;

class SqlitePostsRepository implements PostsRepositoryInterface
{

    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, username_uuid, title, text)
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

    }

    public function get(UUID $uuid): Comment
    {

    }
}