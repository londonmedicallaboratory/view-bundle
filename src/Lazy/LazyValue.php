<?php

declare(strict_types=1);

namespace LML\View\Lazy;

use Closure;

/**
 * @template T
 *
 * @implements LazyValueInterface<T>
 */
class LazyValue implements LazyValueInterface
{
    /**
     * @var Store<T>|null
     */
    private ?Store $store = null;

    /**
     * @param Closure(): T $callable
     */
    public function __construct(private Closure $callable)
    {
    }

    public function __toString(): string
    {
        return (string)$this->getValue();
    }

    /**
     * @return T
     */
    public function getValue()
    {
        $store = $this->store ?? $this->doGetStore();

        return $store->getValue();
    }

    /**
     * @return Store<T>
     */
    private function doGetStore(): Store
    {
        $callable = $this->callable;
        $value = $callable();
        $this->store = new Store($value);

        return $this->store;
    }
}
