<?php

use GeekBrains\LevelTwo\Blog\Commands\Arguments;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\SqliteCommentsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUsersRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');
require_once 'vendor/autoload.php';

$faker = Faker\Factory::create('ru_RU');

$argvArr = Arguments::fromArgv($argv);
$type = $argvArr->get('type');

$sqlComment = new SqliteCommentsRepository($connection);
$sqlUser = new SqliteUsersRepository($connection);
$userComment = $sqlUser->getRandomUser(); //выбираем рандомного пользователя от которого будет комменатрий
$sqlComment->getUserComments($userComment);
die;

$sqlUser = new SqliteUsersRepository($connection);
$user = new User(  //создаем нового пользователя
    UUID::random(),
    $faker->userName(),
    new Name(
        $faker->firstName(),
        $faker->lastName()
    )

);
$sqlUser->save($user); //записываем нового пользователя

echo $user.PHP_EOL;
echo ' --------------------------------------------------'.PHP_EOL;

$sqlPost = new SqlitePostsRepository($connection);
$post = new Post( //создаем статью от имени нового пользователя
    UUID::random(),
    $user,
    $faker->text(60),
    $faker->text(200)

);
$sqlPost->save($post); //сохраняем статью
echo $post.PHP_EOL;
echo ' --------------------------------------------------'.PHP_EOL;

$userComment = $sqlUser->getRandomUser(); //выбираем рандомного пользователя от которого будет комменатрий

$sqlComment = new SqliteCommentsRepository($connection);
$comment = new Comment(
    UUID::random(),
    $userComment,
    $post,
    $faker->text(200)
);
$sqlComment->save($comment);
echo $comment;