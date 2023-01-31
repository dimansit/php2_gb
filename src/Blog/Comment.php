<?php


namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;

class Comment
{

    public function __construct(
        private UUID $uuid,
        private User $user,
        private Post $post,
        private string $text
    )
    {
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }




    public function __toString(): string
    {
        return "Пользователь: $this->user".PHP_EOL.PHP_EOL
            ."написал комментарий: $this->text.".PHP_EOL.PHP_EOL
            ."к посту $this->post".PHP_EOL.PHP_EOL
            ."пост пользователя: {$this->post->getUser()}";
    }
}