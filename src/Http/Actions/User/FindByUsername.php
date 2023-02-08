<?php


namespace GeekBrains\LevelTwo\Http\Actions\User;


use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;

class FindByUsername implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    )
    {
    }

    /**
     * @param Request $request
     * @return SuccessfulResponse
     */
    public function handle(Request $request): Response
    {
        try {
            $username = $request->query('username');
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $user = $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'username' => $user->getUsername(),
            'name' => $user->getName()->first() . ' ' . $user->getName()->last(),
        ]);
    }

}