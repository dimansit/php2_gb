<?php


namespace Geek\Class;


class Comment
{

    private string $uuid;
    private string $author_uuid;
    private string $post_uuid;

    public function __construct(
        private string $text
    )
    {
    }

    public function __toString(): string
    {
        return $this->text;
    }
}