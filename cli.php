<?php

use php2\Article;
use php2\Comment;
use php2\User;

require_once 'vendor/autoload.php';

$faker = Faker\Factory::create('ru_RU');

switch ($argv[1]):
    case 'user':
        $user = new User(
            $faker->firstName(),
            $faker->lastName()
        );
        echo $user;
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
        echo 'Error type params';
endswitch;