<?php


namespace GeekBrains\LevelTwo\Http\Actions\Likes;


use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Like;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;
use PHPUnit\Framework\InvalidArgumentException;

class AddLike implements ActionInterface
{

    /**
     * AddLike constructor.
     * @param PostsRepositoryInterface $postsRepository
     * @param UsersRepositoryInterface $usersRepository
     * @param LikesRepositoryInterface $likesRepository
     */
    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private UsersRepositoryInterface $usersRepository,
        private LikesRepositoryInterface $likesRepository
    )
    {
    }

    public function handle(Request $request): Response
    {

        $author = $this->getAuthor($request);
        $post = $this->getPost($request);
        $existLike = $this->getLikePostByUser($author, $post);

        if ($existLike)
            return new ErrorResponse('Error: you have already put a bark on this post');

        $newLikeUuid = UUID::random();
        try {
            $like = new Like(
                $newLikeUuid,
                $post,
                $author
            );
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }

        $this->likesRepository->save($like);
        return new SuccessfulResponse([
            'uuid' => (string)$newLikeUuid,
        ]);
    }

    /**
     * @param $user
     * @param $post
     * @return array
     */
    private function getLikePostByUser($user, $post): array
    {
        return $this->likesRepository->findLikePostByUser($user, $post);
    }

    /**
     * @param Request $request
     * @return \GeekBrains\LevelTwo\Blog\User|ErrorResponse
     */
    private function getAuthor(Request $request): Request
    {
        try {
            $authorUuid = new UUID($request->jsonBodyField('user_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $author = $this->usersRepository->get($authorUuid);
        } catch (UserNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return $author;
    }

    /**
     * @param Request $request
     * @return \GeekBrains\LevelTwo\Blog\Post|ErrorResponse
     */
    private function getPost(Request $request): Request
    {
        try {
            $postUuid = new UUID($request->jsonBodyField('post_uuid'));
        } catch (HttpException | InvalidArgumentException $e) {
            return new ErrorResponse($e->getMessage());
        }

        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return $post;
    }
}