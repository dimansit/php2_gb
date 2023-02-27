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
use GeekBrains\LevelTwo\Http\Auth\AuthenticationInterface;
use GeekBrains\LevelTwo\Http\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\Http\Auth\TokenAuthenticationInterface;
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
        private TokenAuthenticationInterface $authentication,
        private LoggerInterface $logger,
    )
    {
    }

    public function handle(Request $request): Response
    {
        $this->logger->info('Start create comment');
        $user = $this->authentication->user($request);
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
            $this->logger->error('Error create comment' . $e->getMessage());
            return new ErrorResponse($e->getMessage());
        }

        $this->commentsRepository->save($comment);
        $this->logger->info('Create comment' . $newCommentUuid);
        return new SuccessfulResponse([
            'uuid' => (string)$newCommentUuid,
        ]);
    }

    /**
     * @param $request
     * @return Post|ErrorResponse
     */
    private function getPost($request): Post|ErrorResponse
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

}