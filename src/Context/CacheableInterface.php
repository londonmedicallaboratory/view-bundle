<?php

declare(strict_types=1);

namespace LML\View\Context;

interface CacheableInterface
{
    /**
     * In most cases you should simply return the ID value. For multi-tenant entities, return compound value like:
     *
     * <code>
     *   return sprintf('%s-%s', $this->id, $this->tenantId);
     * </code>
     */
    public function getBaseName(): string;
}
