<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\Entity;

class ProductFixtureEntity
{
    public function __construct(
        private string $name,
        private float $price,
    )
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }
}
