<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

use Symfony\Component\DependencyInjection\ServiceLocator;

/**
 * Collect all view factories and make them globally available.
 *
 * It avoids the need for #[Required] attribute.
 *
 * Selected tag is interface name.
 */
class ViewFactoryCollection
{
    /**
     * @param ServiceLocator<ViewFactoryInterface> $tagged
     */
    public function __construct(
        private ServiceLocator $tagged,
    )
    {
    }

    /**
     * @template T of ViewFactoryInterface
     *
     * @param class-string<T> $id
     *
     * @return T
     *
     * @psalm-suppress InvalidReturnType
     * @psalm-suppress InvalidReturnStatement
     *
     * Complex nested generic; suppression is allowed here.
     */
    public function get(string $id)
    {
        return $this->tagged->get($id);
    }
}
