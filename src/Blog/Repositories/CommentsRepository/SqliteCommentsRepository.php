<?php


namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;


use GeekBrains\LevelTwo\Blog\Comment;
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

    public function get(UUID $uuid): Comment
    {
        foreach ($this->comment as $comment) {
            if ((string)$comment->uuid() === (string)$uuid) {
                return $comment;
            }
        }
        throw new CommentNotFoundException("User not found: $uuid");

    }


    public function getUserComments(User $user)
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE username_uuid = :username_uuid'
        );
        $statement->execute([
            ':username_uuid' => $user->getUuid(),
        ]);
        return $this->getComment($statement, "Comments user $user");
    }

    private function getComment($statement, $text)
    {
        $result = $statement->fetchAll(PDO::FETCH_CLASS);
        if (false === $result) {
            throw new UserNotFoundException(
                "Cannot find: $text"
            );
        }
        var_dump($result);
        return '';

    }
}