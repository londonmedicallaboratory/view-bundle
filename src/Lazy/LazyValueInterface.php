<?php

declare(strict_types=1);

namespace LML\View\Lazy;

/**
 * @template T
 */
interface LazyValueInterface
{
    /**
     * @return T
     */
    public function getValue();
}
