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

    /**
     * @var list<class-string>
     */
    private array $entityClasses = [];

    public function watch(CacheableInterface $entity): void
    {
        $this->taggedEntities[] = $entity;
    }

    /**
     * @param class-string $className
     */
    public function watchClass(string $className): void
    {
        $this->entityClasses[] = $className;
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

    /**
     * @return list<class-string>
     */
    public function getEntityClasses(): array
    {
        return $this->entityClasses;
    }
}
