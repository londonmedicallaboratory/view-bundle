<?php

declare(strict_types=1);

namespace LML\View\Pagination;

use ArrayObject;
use ArrayIterator;
use Doctrine\ORM\Query;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use LML\View\ViewFactory\ViewFactoryInterface;

/**
 * Adapter for pagination.
 *
 * Accepts regular query but instead of entities, makes iterable of view classes.
 *
 * @template TEntity
 * @template TView
 * @template TOptions of array
 */
class QueryViewAdapter extends QueryAdapter
{
    /**
     * @var ?list<TView>
     */
    private ?array $views = null;

    /**
     * @param ViewFactoryInterface<TEntity, TView, TOptions> $viewFactory
     * @param TOptions $options
     */
    public function __construct(Query $query, private ViewFactoryInterface $viewFactory, private $options = [])
    {
        parent::__construct($query);
    }

    /**
     * @return ArrayIterator<array-key, TView>
     */
    public function getSlice(int $offset, int $length): iterable
    {
        $views = $this->views ??= $this->doGetSlice($offset, $length);
        $arrayObject = new ArrayObject($views);

        return $arrayObject->getIterator();
    }

    /**
     * @return list<TView>
     */
    private function doGetSlice(int $offset, int $length)
    {
        $entities = parent::getSlice($offset, $length);

        return $this->viewFactory->build($entities, $this->options);
    }
}
