<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

use Closure;
use Traversable;
use RuntimeException;
use Doctrine\ORM\Query;
use Pagerfanta\Pagerfanta;
use LML\View\Lazy\LazyValue;
use LML\View\Context\CacheContext;
use LML\View\Context\CacheableInterface;
use LML\View\Pagination\QueryViewAdapter;
use function iterator_to_array;

/**
 * @template TEntity
 * @template-covariant TView
 * @template TOptions of array
 * @template TOptimizer
 *
 * @implements ViewFactoryInterface<TEntity, TView, TOptions>
 */
abstract class AbstractViewFactory implements ViewFactoryInterface
{
    private ?ViewFactoryCollection $viewFactoriesCollection = null;

    private ?CacheWrapper $cacheWrapper = null;

    final public static function getDefaultName(): string
    {
        return self::class;
    }

    /**
     * @param TEntity $entity
     * @param TOptions $options
     *
     * @return TView
     */
    final public function buildOne($entity, $options = [])
    {
        $optimizer = new LazyValue(fn() => $this->createOptimizerWrapper([$entity], $options));

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
        $optimizer = new LazyValue(fn() => $this->createOptimizerWrapper($entities, $options));
        foreach ($entities as $entity) {
            $results[] = $this->one($entity, $options, $optimizer);
        }

        return $results;
    }

    /**
     * @param TOptions $options
     *
     * @return Pagerfanta<TView>
     */
    final public function paginateFromQuery(Query $query, int $page, $options = [], int $maxPerPage = 10): Pagerfanta
    {
        $adapter = new QueryViewAdapter($query, $this, $options);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage($maxPerPage);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
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
     * @param LazyValue<TOptimizer> $optimizer
     *
     * @return TView
     */
    abstract protected function one($entity, $options, LazyValue $optimizer);

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
     * @param TBaseEntity $entity
     * @param Closure(CacheContext, TBaseEntity): TResult $callback
     *
     * @return TResult
     *
     * @see Context
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
