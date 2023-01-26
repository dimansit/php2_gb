<?php


namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\UUID;

class User
{

    public function __construct(
        private UUID $uuid,
        private string $username,
        private string $first_name,
        private string $last_name
    )
    {
    }

    /**
     * @return \GeekBrains\LevelTwo\Blog\UUID
     */
    public function getUuid(): \GeekBrains\LevelTwo\Blog\UUID
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
    public function getFirstName(): string
    {
        return $this->first_name;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->last_name;
    }


    public function __toString(): string
    {
        return $this->uuid.' '.$this->first_name . ' ' . $this->last_name;
    }
}
