<?php


namespace GeekBrains\LevelTwo\Blog\Commands;


use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PopulateDB extends Command
{
    public function __construct(
        private \Faker\Generator $faker,
        private UsersRepositoryInterface $usersRepository,
        private PostsRepositoryInterface $postsRepository,
        private CommentsRepositoryInterface $commentsRepository
    )
    {
        parent::__construct();
    }


    protected function configure(): void
    {
        $this
            ->setName('fake-data:populate-db')
            ->setDescription('Populates DB with fake data')
            ->addOption(
                'users-number',
                'un',
                InputOption::VALUE_OPTIONAL,
                'User add counter',
            )
            ->addOption(
                'posts-number',
                'pn',
                InputOption::VALUE_OPTIONAL,
                'Post add counter',
            )
            ->addOption(
                'comments-number',
                'cn',
                InputOption::VALUE_OPTIONAL,
                'Post add counter',
            );;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int
    {
        $countUsersAdd = $input->getOption('users-number') ?: 2;
        $countPostsAdd = $input->getOption('posts-number') ?: 5;
        $countCommentsAdd = $input->getOption('comments-number') ?: 1;
        $output->writeln("$countUsersAdd users will be added");
        $users = [];
        $posts = [];
        for ($i = 0; $i < $countUsersAdd; $i++) {
            $user = $this->createFakeUser();
            $users[] = $user;
            $output->writeln('User created: ' . $user->getUsername());
        }
        $output->writeln("$countPostsAdd  posts from each user will be added");
        foreach ($users as $user) {
            for ($i = 0; $i < $countPostsAdd; $i++) {
                $post = $this->createFakePost($user);
                $posts[] = $post;
                $output->writeln('Post created: ' . $post->getTitle());
            }
        }
        $output->writeln("$countCommentsAdd  comment from each post will be added");
        foreach ($posts as $post) {
            for ($i = 0; $i < $countCommentsAdd; $i++) {
                $comment = $this->createFakeComment($post, $users[array_rand($users)]);
                $output->writeln('Comment created: ' . $comment->getText());
            }
        }
        return Command::SUCCESS;
    }

    /**
     * @return User
     */
    private function createFakeUser(): User
    {
        $user = User::createFrom(
            $this->faker->userName,
            $this->faker->password,
            new Name(
                $this->faker->firstName,
                $this->faker->lastName
            )
        );
        $this->usersRepository->save($user);
        return $user;
    }

    /**
     * @param User $author
     * @return Post
     */
    private function createFakePost(User $author): Post
    {
        $post = new Post(
            UUID::random(),
            $author,
            $this->faker->sentence(6, true),
            $this->faker->realText
        );
        $this->postsRepository->save($post);
        return $post;
    }


    /**
     * @param Post $post
     * @param User $user
     * @return Comment
     */
    private function createFakeComment(Post $post, User $user): Comment
    {
        $comment = new Comment(
            UUID::random(),
            $user,
            $post,
            $this->faker->realText
        );
        $this->commentsRepository->save($comment);
        return $comment;
    }

}