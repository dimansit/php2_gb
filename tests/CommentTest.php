<?php


use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class CommentTest extends TestCase
{
    public function testCommentGets(): void
    {
        $comment = new Comment(
            new UUID('589351ac-e010-4c05-b271-7c6793b8eebf'),
            new User(
                new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
                'login',
                'pass',
                new Name('fname', 'lname')
            ),
            new Post(
                new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
                new User(
                    new UUID('789351ac-e010-4c05-b271-7c6793b8eebf'),
                    'login',
                    'pass',
                    new Name('fname', 'lname')
                ),
                'title',
                'text'
            ),
            'comment'
        );
        $value = $comment->getUuid();
        $this->assertEquals('589351ac-e010-4c05-b271-7c6793b8eebf', $value);

        $comment = $comment->getText();
        $this->assertEquals('comment', $comment);


    }

}