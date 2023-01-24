<?php


namespace php2\Class;

class User
{
    private string $uuid;
    private string $username;

    public function __construct(
        private string $first_name,
        private string $last_name
    )
    {
    }

    public function __toString(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
