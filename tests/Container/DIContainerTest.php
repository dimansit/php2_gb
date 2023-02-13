<?php

namespace GeekBrains\Blog\UnitTests\Container;

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Container\NotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use Monolog\Logger;
use PDO;
use GeekBrains\Blog\UnitTests\DummyLogger;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class DIContainerTest extends TestCase
{

    public function testItResolvesClassWithDependencies(): void
    {

        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(ClassDependingOnAnother::class);

        $this->assertInstanceOf(
            ClassDependingOnAnother::class,
            $object
        );
    }


    public function testItReturnsPredefinedObject(): void
    {

        $container = new DIContainer();

        $container->bind(
            SomeClassWithParameter::class,
            new SomeClassWithParameter(42)
        );

        $object = $container->get(SomeClassWithParameter::class);

        $this->assertInstanceOf(
            SomeClassWithParameter::class,
            $object
        );

        $this->assertSame(42, $object->value());
    }

    public function testItResolvesClassByContract(): void
    {
        $container = new DIContainer();
        $container->bind(
            PDO::class,
            new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
        );
        $container->bind(
            LoggerInterface::class,
            new DummyLogger()
        );
        $container->bind(
            UsersRepositoryInterface::class,
            SqliteUsersRepository::class
        );
        $object = $container->get(UsersRepositoryInterface::class);

        $this->assertInstanceOf(
            SqliteUsersRepository::class,
            $object
        );
    }


    public function testItResolvesClassWithoutDependencies(): void
    {
        $container = new DIContainer();
        $object = $container->get(SomeClassWithoutDependencies::class);
        $this->assertInstanceOf(
            SomeClassWithoutDependencies::class,
            $object
        );
    }

    public function testItThrowsAnExceptionIfCannotResolveType(): void
    {

        $container = new DIContainer();

        $this->expectException(\GeekBrains\LevelTwo\Blog\Exceptions\NotFoundException::class);
        $this->expectExceptionMessage(
            'Cannot resolve type: GeekBrains\Blog\UnitTests\Container\SomeClass'
        );
        $container->get(SomeClass::class);

    }

}