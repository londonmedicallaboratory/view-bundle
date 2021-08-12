<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\View;

use LML\View\Lazy\LazyValue;
use function in_array;

class VideoView
{
    /**
     * @param LazyValue<list<int>> $collectionOfLikes
     */
    public function __construct(
        public int $id,
        public string $title,
        private LazyValue $collectionOfLikes,
    )
    {
    }

    public function isLikedByCurrentUser(): bool
    {
        $likes = $this->collectionOfLikes->getValue();

        return in_array($this->id, $likes, true);
    }
}
