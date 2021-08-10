<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\Entity;

class CategoryFixtureEntity
{
    public function __construct(
        private string $name,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }
}
