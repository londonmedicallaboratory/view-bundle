<?php

declare(strict_types=1);

namespace LML\View\Lazy;

/**
 * @template T
 *
 * @implements LazyValueInterface<T>
 */
class ResolvedValue implements LazyValueInterface
{
    /**
     * @param T $value
     */
    public function __construct(private $value)
    {
    }

    public function isEvaluated(): bool
    {
        return true;
    }

    public function getValue()
    {
        return $this->value;
    }
}
