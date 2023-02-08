<?php


namespace GeekBrains\LevelTwo\Blog;


class Like
{
    public function __construct(
        private UUID $uuid,
        private Post $post,
        private User $user
    )
    {
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return UUID
     */
    public function getUuid(): UUID
    {
        return $this->uuid;
    }
}