<?php

namespace php2\Class;

class Article
{
    private int $id;
    private int $idUser;

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