<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\Entity;

use function sprintf;

class User
{
    public function __construct(
        private int $id,
        private string $firstName,
        private string $lastName,
    )
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return sprintf('%s %s', $this->firstName, $this->lastName);
    }
}
