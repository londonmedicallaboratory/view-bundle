<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

/**
 * @template T
 * @template-covariant  R
 * @template O of array
 *
 * @see ViewFactoryCollection
 */
interface ViewFactoryInterface
{
    /**
     * @param T $entity
     * @param O $options
     *
     * @return R
     */
    public function buildOne($entity, $options = []);

    /**
     * @param iterable<T> $entities
     * @param O $options
     *
     * @return list<R>
     */
    public function build(iterable $entities, $options = []);

}
