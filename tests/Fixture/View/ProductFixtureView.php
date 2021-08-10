<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\View;

use LML\View\Lazy\LazyValue;

class ProductFixtureView
{
    /**
     * @param LazyValue<CategoryFixtureView> $category
     */
    public function __construct(
        private string $name,
        private LazyValue $category,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): CategoryFixtureView
    {
        return $this->category->getValue();
    }
}
