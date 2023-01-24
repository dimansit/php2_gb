<?php


namespace php2;


class Comment
{

    private int $id;
    private int $userId;
    private int $articleId;

    public function __construct(
        private string $comment
    )
    {
    }

    public function __toString(): string
    {
        return $this->comment;
    }
}