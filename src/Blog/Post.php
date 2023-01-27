<?php

namespace GeekBrains\LevelTwo\Blog;


class Post
{

    public function __construct(
        private UUID $uuid,
        private User $user,
        private string $title,
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
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
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
        return "ПОлзователь: $this->user" . PHP_EOL
            . "Написал статью: $this->title  текст статьи: $this->text";
    }
}