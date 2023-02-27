<?php

namespace GeekBrains\LevelTwo\Blog\Commands\Posts;

use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

class DeletePost extends Command
{
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('posts:delete')
            ->setDescription('Deletes a post')
            ->addArgument(
                'uuid',
                InputArgument::REQUIRED,
                'UUID of a post to delete'
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int
    {
        $question = new ConfirmationQuestion(
            'Delete post [Y/n]? ',
            false
        );
        if (!$this->getHelper('question')
            ->ask($input, $output, $question)
        ) {
            return Command::SUCCESS;
        }
        $uuid = new UUID($input->getArgument('uuid'));
        $this->postsRepository->delete($uuid);
        $output->writeln("Post $uuid deleted");
        return Command::SUCCESS;
    }
}
