<?php

declare(strict_types=1);

namespace LML\View\Lazy;

/**
 * This should not be used outside of LazyValue class.
 *
 * @template T
 *
 * @internal
 * @psalm-internal LML\View\Lazy
 */
class Store
{
    /**
     * @param T $value
     */
    public function __construct(
        private $value,
    )
    {
    }

    /**
     * @return T
     */
    public function getValue()
    {
        return $this->value;
    }
}
