<?php

declare(strict_types=1);

namespace LML\View\Lazy;

use Closure;
use Generator;

/**
 * @template TKey
 * @template TValue
 *
 * @implements LazyIterableInterface<TKey, TValue>
 */
class LazyIterable implements LazyIterableInterface
{
    /**
     * @var Store<iterable<TKey, TValue>>|null
     */
    private ?Store $store = null;

    /**
     * @param Closure(): iterable<TKey, TValue> $callable
     */
    public function __construct(private Closure $callable)
    {
    }

    /**
     * @param TValue $element
     */
    public function contains($element): bool
    {
        foreach ($this->getValues() as $value) {
            if ($value === $element) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return iterable<TKey, TValue>
     */
    public function getValues()
    {
        $store = $this->store ?? $this->doGetStore();

        return $store->getValue();
    }

    /**
     * @return Generator<TKey, TValue>
     */
    public function getIterator(): Generator
    {
        yield from $this->getValues();
    }

    /**
     * @return Store<iterable<TKey, TValue>>
     */
    private function doGetStore(): Store
    {
        $callable = $this->callable;
        $value = $callable();
        $this->store = new Store($value);

        return $this->store;
    }
}
