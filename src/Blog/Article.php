<?php

namespace php2\Class;

class Article
{
    private string $uuid;
    private string $author_uuid;

    public function __construct(
        private string $title,
        private string $text
    )
    {
    }

    public function __toString(): string
    {
        return $this->title . ' >> ' . $this->text;
    }
}