<?php


namespace GeekBrains\LevelTwo\Http\Actions\Comments;


use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository\CommentsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use PHPUnit\Framework\InvalidArgumentException;
use Psr\Log\LoggerInterface;

class CreateComment implements ActionInterface
{

    public function __construct(
        private CommentsRepositoryInterface $commentsRepository,
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        $user = $this->getUser($request);
        $post = $this->getPost($request);
        $newCommentUuid = UUID::random();
        try {
            $comment = new Comment(
                $newCommentUuid,
                $user,
                $post,
                $request->jsonBodyField('text'),
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);
        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }

    /**
     * @param $request
     * @return Post|ErrorResponse
     */
    private function getPost($request)
    {
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return $post;
    }

    /**
     * @param Request $request
     * @return User|ErrorResponse
     */
    private function getUser(Request $request): User|ErrorResponse
    {
        try {
            $userUuid = new UUID($request->jsonBodyField('author_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $user = $this->usersRepository->get($userUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return $user;
    }
}