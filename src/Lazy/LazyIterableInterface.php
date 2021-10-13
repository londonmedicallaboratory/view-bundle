<?php

declare(strict_types=1);

namespace LML\View\Lazy;

use IteratorAggregate;

/**
 * @template T
 *
 * @extends IteratorAggregate<array-key, T>
 */
interface LazyIterableInterface extends IteratorAggregate
{
    /**
     * @return iterable<array-key, T>
     */
    public function getValues();

    /**
     * @return list<T>
     */
    public function toList(): array;
}
