<?php

declare(strict_types=1);

namespace LML\View\Context;

class CacheContext
{
    /**
     * @var list<CacheableInterface>
     */
    private array $taggedEntities = [];

    private ?int $expiresAfter = null;

    public function watch(CacheableInterface $entity): void
    {
        $this->taggedEntities[] = $entity;
    }

    /**
     * @return list<CacheableInterface>
     */
    public function getTaggedEntities(): array
    {
        return $this->taggedEntities;
    }

    public function getExpiresAfter(): ?int
    {
        return $this->expiresAfter;
    }

    public function expiresAfter(?int $expiresAfter): void
    {
        $this->expiresAfter = $expiresAfter;
    }
}
