<?php


namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;


use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;

class SqliteCommentsRepository implements CommentsRepositoryInterface
{
    public function __construct(
        private PDO $connection
    )
    {
    }

    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, username_uuid, post_uuid, text)
                   VALUES (
                           :uuid, 
                           :username_uuid,
                           :post_uuid, 
                           :text
                           )'
        );

        $statement->execute([
            ':uuid' => $comment->getUuid(),
            ':username_uuid' => $comment->getUser()->getUuid(),
            ':post_uuid' => $comment->getPost()->getUuid(),
            ':text' => $comment->getText()
        ]);
    }

    /**
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     * @throws CommentNotFoundException
     */
    public function get(UUID $uuid): Comment
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => $uuid,
        ]);

        return $this->getComment($statement, $uuid);
    }


    /**
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException
     * @throws CommentNotFoundException
     */
    private function getComment(\PDOStatement $statement, $text): Comment
    {
        $result = $statement->fetch(PDO::FETCH_CLASS);
        if (!$result) {
            throw new CommentNotFoundException(
                "Cannot find: $text"
            );
        }
        $user = new SqliteUsersRepository($this->connection);
        $post = new SqlitePostsRepository($this->connection);
        return new Comment(
            new UUID($result['uuid']),
            $user->get(new UUID($result['username_uuid'])),
            $post->get(new UUID($result['post_uuid'])),
            $result['text']
        );

    }
}