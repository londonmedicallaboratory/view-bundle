<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

use Closure;
use RuntimeException;
use Doctrine\ORM\Query;
use Pagerfanta\Pagerfanta;
use LML\View\Context\CacheContext;
use LML\View\Context\CacheableInterface;
use LML\View\Pagination\QueryViewAdapter;

/**
 * Abstract factory to create view classes from entities.
 * Future versions will have tagged caching; all relations will still be able to display results, even without queries being executed.
 *
 * The logic is pretty simple: object cannot be displayed, but scalars like strings and floats can.
 * HTML, API... doesn't matter. In the end, only these scalars are important to user.
 *
 * When child factory depends on other factory, use @see AbstractViewFactory::get() method. This will prevent possible recursion, same problem DoctrineBundle had.
 *
 * @template T
 * @template-covariant  R
 * @template O of array
 *
 * @implements ViewFactoryInterface<T, R, O>
 */
abstract class AbstractViewFactory implements ViewFactoryInterface
{
    private ?ViewFactoryCollection $viewFactoriesCollection = null;

    private ?CacheWrapper $cacheWrapper = null;

    public static function getDefaultName(): string
    {
        return self::class;
    }

    /**
     * @param T $entity
     * @param O $options
     *
     * @return R
     */
    public function buildOne($entity, $options = [])
    {
        return $this->one($entity, $options);
    }

    /**
     * @param iterable<T> $entities
     * @param O $options
     *
     * @return list<R>
     */
    public function build(iterable $entities, $options = [])
    {
        $results = [];
        foreach ($entities as $entity) {
            $results[] = $this->one($entity, $options);
        }

        return $results;
    }

    /**
     * @param O $options
     *
     * @return Pagerfanta<R>
     */
    public function paginateFromQuery(Query $query, int $page, $options = [], int $maxPerPage = 10): Pagerfanta
    {
        $adapter = new QueryViewAdapter($query, $this, $options);

        $pagerfanta = new Pagerfanta($adapter);
        $pagerfanta->setMaxPerPage(10);
        $pagerfanta->setCurrentPage($page);

        return $pagerfanta;
    }

    public function setViewFactoriesCollection(ViewFactoryCollection $viewFactoriesCollection): void
    {
        $this->viewFactoriesCollection = $viewFactoriesCollection;
    }

    public function setCacheWrapper(CacheWrapper $cacheWrapper): void
    {
        $this->cacheWrapper = $cacheWrapper;
    }

    /**
     * @param T $entity
     * @param O $options
     *
     * @return R
     */
    abstract protected function one($entity, $options);

    /**
     * @template TView of ViewFactoryInterface
     *
     * @param class-string<TView> $id
     *
     * @psalm-return TView
     */
    protected function get(string $id): ViewFactoryInterface
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
    protected function fromCache(string $key, CacheableInterface $entity, Closure $callback)
    {
        $tagAwareCache = $this->cacheWrapper ?? throw new RuntimeException('DI failure');

        return $tagAwareCache->get($key, $entity, $callback);
    }
}
