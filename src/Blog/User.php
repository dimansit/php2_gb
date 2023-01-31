<?php


namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

class User
{

    public function __construct(
        private UUID $uuid,
        private string $username,
        private Name $name
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
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }


    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->name->last();
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->username}, имя {$this->name->last()} {$this->name->first()}";
    }
}
