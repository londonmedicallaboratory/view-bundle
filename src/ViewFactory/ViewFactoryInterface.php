<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

/**
 * @template TEntity
 * @template-covariant TView
 * @template TOptions of array
 *
 * @see ViewFactoryCollection
 */
interface ViewFactoryInterface
{
    /**
     * @param TEntity $entity
     * @param TOptions $options
     *
     * @return TView
     */
    public function buildOne($entity, $options = []);

    /**
     * @param iterable<TEntity> $entities
     * @param TOptions $options
     *
     * @return list<TView>
     */
    public function build(iterable $entities, $options = []);
}
