<?php

declare(strict_types=1);

namespace LML\View\Lazy;

use Closure;
use Generator;
use Traversable;
use function array_values;
use function iterator_to_array;

/**
 * @template T
 *
 * @implements LazyIterableInterface<T>
 */
class LazyIterable implements LazyIterableInterface
{
    /**
     * @var Store<iterable<array-key, T>>|null
     */
    private ?Store $store = null;

    /**
     * @param Closure(): iterable<array-key, T> $callable
     */
    public function __construct(private Closure $callable)
    {
    }

    /**
     * @param T $element
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
     * @return iterable<array-key, T>
     */
    public function getValues(): iterable
    {
        $store = $this->store ?? $this->doGetStore();

        return $store->getValue();
    }

    /**
     * @return Generator<array-key, T>
     */
    public function getIterator(): Generator
    {
        yield from $this->getValues();
    }

    public function toList(): array
    {
        $values = $this->getValues();
        if ($values instanceof Traversable) {
            $values = iterator_to_array($values, false);
        }

        return array_values($values);
    }

    /**
     * @return Store<iterable<array-key, T>>
     */
    private function doGetStore(): Store
    {
        $callable = $this->callable;
        $value = $callable();
        $this->store = new Store($value);

        return $this->store;
    }
}
