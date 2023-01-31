<?php


use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserGets(): void
    {
        $user = new User(
            new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
            'login',
            new Name('fname', 'lname')
        );
        $value = $user->getUuid();
        $this->assertEquals('789351ac-e010-4c05-b271-7c6793b8eebf', $value);

        $username = $user->getUsername();
        $this->assertEquals('login',$username);

        $username = $user->getLastName();
        $this->assertEquals('lname',$username);

        $name = $user->getName();
        $this->assertEquals('fname lname', $name);
    }

}