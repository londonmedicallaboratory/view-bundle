<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\ViewFactory;

use LML\View\Lazy\LazyValue;
use LML\View\Tests\Fixture\Entity\User;
use LML\View\Tests\Fixture\Entity\Video;
use LML\View\Tests\Fixture\View\VideoView;
use LML\View\ViewFactory\AbstractViewFactory;
use function random_int;

/**
 * @extends AbstractViewFactory<Video, VideoView, array{user: User}, array{liked: LazyValue<list<int>>}>
 *
 * @see Video
 * @see VideoView
 * @see User
 */
class VideoViewFactory extends AbstractViewFactory
{
    protected function one($entity, $options, $optimizer): VideoView
    {
        return new VideoView(
            id: $entity->getId(),
            title: $entity->getTitle(),
            collectionOfLikes: $optimizer['liked']
        );
    }

    protected function createOptimizer(array $entities, $options)
    {
        return [
            'liked' => new LazyValue(fn() => $this->createIsLikedByUser()),
        ];
    }

    /**
     * @param list<Video> $entities
     *
     * @return list<int>
     */
    private function createIsLikedByUser()
    {
        return [
            random_int(1, 100),
        ];
    }

}
