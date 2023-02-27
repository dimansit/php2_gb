<?php


use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class PostTest extends TestCase
{
    public function testPostGets(): void
    {
        $post = new Post(
            new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
            new User(
                new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
                'login',
                'pass',
                new Name('fname', 'lname')
            ),
            'title',
            'text'
        );
        $value = $post->getUuid();
        $this->assertEquals('789351ac-e010-4c05-b271-7c6793b8eebf', $value);

        $title = $post->getTitle();
        $this->assertEquals('title', $title);

        $text = $post->getText();
        $this->assertEquals('text', $text);

    }

}