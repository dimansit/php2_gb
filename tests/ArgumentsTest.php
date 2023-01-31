<?php


use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use PHPUnit\Framework\TestCase;

class ArgumentsTest extends TestCase
{
    /**
     * @throws ArgumentsException
     */
    public function testAgrumentGet(): void
    {
        $arg = new Arguments([
            'ind1' => 'val',
            'ind2' => 'val2'
        ]);

        $val = $arg->get('ind1');
        $this->assertEquals('val', $val);


        $this->expectException(ArgumentsException::class);
        $this->expectExceptionMessage('No such argument: 21212');
        $val = $arg->get('21212');
    }

    /**
     * @throws ArgumentsException
     */
    public function testFromArgv()
    {
        $arg = Arguments::fromArgv([
            'ind1=val',
            'ind2=val2'
        ]);

        $val = $arg->get('ind2');
        $this->assertEquals('val2', $val);
    }

}