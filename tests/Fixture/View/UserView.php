<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\View;

use LML\View\Lazy\LazyValue;

class UserView
{
    /**
     * @param LazyValue<VideoView> $category
     */
    public function __construct(
        private string $name,
        public int $id,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
