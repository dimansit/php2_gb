<?php


namespace GeekBrains\LevelTwo\Http\Actions\Likes;


use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\LikeNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Http\Actions\ActionInterface;
use GeekBrains\LevelTwo\Http\ErrorResponse;
use GeekBrains\LevelTwo\Http\Request;
use GeekBrains\LevelTwo\Http\Response;
use GeekBrains\LevelTwo\Http\SuccessfulResponse;


class FindLikesByPost implements ActionInterface
{

    public function __construct(
        private PostsRepositoryInterface $postsRepository,
        private LikesRepositoryInterface $likesRepository
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
            $postUuid = new UUID($request->query('post_uuid'));
        } catch (HttpException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $post = $this->postsRepository->get($postUuid);
        } catch (PostNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        try {
            $likes = $this->likesRepository->getByLikesUuid($post->getUuid());
        } catch (LikeNotFoundException $e) {
            return new ErrorResponse($e->getMessage());
        }
        return new SuccessfulResponse([
            'likes' => $likes,
        ]);
    }
}