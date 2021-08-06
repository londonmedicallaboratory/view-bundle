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
 * @template T
 * @template R
 * @template O of array
 */
class QueryViewAdapter extends QueryAdapter
{
    private ?array $views = null;

    /**
     * @param ViewFactoryInterface<T, R, O> $viewFactory
     * @param O $options
     */
    public function __construct(Query $query, private ViewFactoryInterface $viewFactory, private $options = [])
    {
        parent::__construct($query);
    }

    /**
     * @return ArrayIterator<array-key, R>
     */
    public function getSlice(int $offset, int $length): iterable
    {
        $views = $this->views ??= $this->doGetSlice($offset, $length);
        $arrayObject = new ArrayObject($views);

        return $arrayObject->getIterator();
    }

    /**
     * @return list<R>
     */
    private function doGetSlice(int $offset, int $length)
    {
        $entities = parent::getSlice($offset, $length);

        return $this->viewFactory->build($entities, $this->options);
    }
}
