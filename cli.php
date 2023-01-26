<?php

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Article;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create('ru_RU');

/**
 * пока реалиция через свитч...
 */

switch ($argv[1]):
    case 'user':
        $sql = new SqliteUsersRepository($connection);

        $user = new User(
            UUID::random(),
            $faker->name(),
            new Name(
                $faker->firstName(),
                $faker->lastName()
            )

        );
        echo $user.PHP_EOL;
        $sql->save($user);


        break;
    case 'post':
        $article = new Article(
            $faker->text(60),
            $faker->text(200)
        );
        echo $article;
        break;
    case 'comment':
        $comment = new Comment(
            $faker->text(40)
        );
        echo $comment;
        break;
    default:
        echo 'Error input params';
endswitch;