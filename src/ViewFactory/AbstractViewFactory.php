<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

use Closure;
use Traversable;
use RuntimeException;
use LML\View\Context\CacheContext;
use LML\View\Lazy\LazyValueInterface;
use LML\View\Context\CacheableInterface;
use function iterator_to_array;

/**
 * @template TEntity
 * @template-covariant TView
 * @template TOptions of array
 * @template TOptimizer of array
 *
 * @implements ViewFactoryInterface<TEntity, TView, TOptions>
 *
 * TOptimizer should be array<string, LazyValueInterface>
 *
 * @see LazyValueInterface
 */
abstract class AbstractViewFactory implements ViewFactoryInterface
{
    private ?ViewFactoryCollection $viewFactoriesCollection = null;

    private ?CacheWrapper $cacheWrapper = null;

    final public static function getDefaultName(): string
    {
        return static::class;
    }

    /**
     * @param TEntity $entity
     * @param TOptions $options
     *
     * @return TView
     */
    final public function buildOne($entity, $options = [])
    {
        $optimizer = $this->createOptimizerWrapper([$entity], $options);

        return $this->one($entity, $options, $optimizer);
    }

    /**
     * @param iterable<TEntity> $entities
     * @param TOptions $options
     *
     * @return list<TView>
     */
    final public function build(iterable $entities, $options = [])
    {
        $results = [];
        $optimizer = $this->createOptimizerWrapper($entities, $options);
        foreach ($entities as $entity) {
            $results[] = $this->one($entity, $options, $optimizer);
        }

        return $results;
    }

    final public function setViewFactoriesCollection(ViewFactoryCollection $viewFactoriesCollection): void
    {
        $this->viewFactoriesCollection = $viewFactoriesCollection;
    }

    final public function setCacheWrapper(CacheWrapper $cacheWrapper): void
    {
        $this->cacheWrapper = $cacheWrapper;
    }

    /**
     * @param TEntity $entity
     * @param TOptions $options
     * @param TOptimizer $optimizer
     *
     * @return TView
     */
    abstract protected function one($entity, $options, $optimizer);

    /**
     * @template TViewFactory of ViewFactoryInterface
     *
     * @param class-string<TViewFactory> $id
     *
     * @psalm-return TViewFactory
     */
    final protected function get(string $id): ViewFactoryInterface
    {
        return $this->viewFactoriesCollection?->get($id) ?? throw new RuntimeException('DI failure.');
    }

    /**
     * @template TResult
     * @template TBaseEntity of CacheableInterface
     *
     * @see Context
     * @param Closure(CacheContext, TBaseEntity): TResult $callback
     *
     * @param TBaseEntity $entity
     * @return TResult
     *
     */
    final protected function fromCache(string $key, CacheableInterface $entity, Closure $callback)
    {
        $tagAwareCache = $this->cacheWrapper ?? throw new RuntimeException('DI failure');

        return $tagAwareCache->get($key, $entity, $callback);
    }

    /**
     * Override this method for collection optimization.
     * Method will always get array of entities, not Traversable.
     *
     * Return value of this method will be 3rd parameter of ``one``
     *
     * @param array<array-key, TEntity> $entities
     * @param TOptions $options
     *
     * @return TOptimizer
     *
     * @noinspection PhpUnusedParameterInspection
     */
    protected function createOptimizer(array $entities, $options)
    {
        return null;
    }

    /**
     * @param iterable<TEntity> $entities
     * @param TOptions $options
     *
     * @return TOptimizer
     */
    private function createOptimizerWrapper(iterable $entities, $options)
    {
        if ($entities instanceof Traversable) {
            $entities = iterator_to_array($entities, false);
        }

        return $this->createOptimizer($entities, $options);
    }
}
