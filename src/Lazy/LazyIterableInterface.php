<?php

declare(strict_types=1);

namespace LML\View\Lazy;

use IteratorAggregate;

/**
 * @template TKey
 * @template TValue
 *
 * @extends IteratorAggregate<TKey, TValue>
 */
interface LazyIterableInterface extends IteratorAggregate
{
    /**
     * @return iterable<TKey, TValue>
     */
    public function getValues();
}
