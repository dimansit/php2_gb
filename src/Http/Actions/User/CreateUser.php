<?php


namespace GeekBrains\LevelTwo\Http\Actions\User;


use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use GeekBrains\LevelTwo\Person\Name;

class CreateUser implements ActionInterface
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
    )
    {
    }

    /**
     * @param Request $request
     * @return SuccessfulResponse
     * @throws \GeekBrains\LevelTwo\Blog\Exceptions\HttpException
     */
    public function handle(Request $request): Response
    {
        $newUserUuid = UUID::random();
        try {
            $user = new User(
                $newUserUuid,
                $request->jsonBodyField('username'),
                new Name(
                    $request->jsonBodyField('first_name'),
                    $request->jsonBodyField('last_name')
                )
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        $this->usersRepository->save($user);


        return new SuccessfulResponse([
            'uuid' => $user->getUuid(),
            'name'=> $user->getName(),
            'username' => $user->getUsername()
        ]);
    }
}
