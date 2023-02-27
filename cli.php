<?php

use GeekBrains\LevelTwo\Blog\Commands\PopulateDB;
use GeekBrains\LevelTwo\Blog\Commands\Users\CreateUser;
use GeekBrains\LevelTwo\Blog\Commands\Posts\DeletePost;
use GeekBrains\LevelTwo\Blog\Commands\Users\UpdateUser;
use Symfony\Component\Console\Application;

$container = require __DIR__ . '/bootstrap.php';
$application = new Application();

$commandsClasses = array(
    CreateUser::class,
    DeletePost::class,
    UpdateUser::class,
    PopulateDB::class,

);

foreach ($commandsClasses as $commandClass) {
    $command = $container->get($commandClass);
    $application->add($command);
}
try {
    $application->run();
} catch (Exception $e) {
}