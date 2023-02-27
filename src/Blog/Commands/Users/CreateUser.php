<?php

namespace GeekBrains\LevelTwo\Blog\Commands\Users;

use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\Name;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUser extends Command
{
    /**
     * CreateUser constructor.
     * @param UsersRepositoryInterface $usersRepository
     */
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    )
    {
        parent::__construct();
    }

    /**
     *
     */
    protected function configure(): void
    {
        $this->setName('users:create')
            ->setDescription('Creates new user')
            ->addArgument(
                'first_name',
                InputArgument::REQUIRED,
                'First name'
            )
            ->addArgument('last_name', InputArgument::REQUIRED, 'Last name')
            ->addArgument('username', InputArgument::REQUIRED, 'Username')
            ->addArgument('password', InputArgument::REQUIRED, 'Password');
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
        $output->writeln('Create user command started');
        $username = $input->getArgument('username');
        if ($this->userExists($username)) {
            $output->writeln("User already exists: $username");
            return Command::FAILURE;
        }
        $user = User::createFrom(
            $username,
            $input->getArgument('password'),
            new Name(
                $input->getArgument('first_name'),
                $input->getArgument('last_name')
            )
        );
        $this->usersRepository->save($user);
        $output->writeln('User created: ' . $user->getUuid());
        return Command::SUCCESS;
    }

    /**
     * @param string $username
     * @return bool
     */
    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}
