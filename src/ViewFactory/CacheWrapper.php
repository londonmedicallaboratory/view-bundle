<?php

declare(strict_types=1);

namespace LML\View\ViewFactory;

use Closure;
use LML\View\Context\CacheContext;
use Doctrine\Common\Util\ClassUtils;
use LML\View\Context\CacheableInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use function sprintf;
use function str_replace;

class CacheWrapper
{
    public function __construct(
        private TagAwareCacheInterface $tagAwareCache,
    )
    {
    }

    /**
     * @template TResult
     * @template TBaseEntity of CacheableInterface
     *
     * @param TBaseEntity $entity
     * @param Closure(CacheContext, TBaseEntity): TResult $callback
     *
     * @return TResult
     */
    public function get(string $key, CacheableInterface $entity, Closure $callback)
    {
        $entityKey = $this->generateEntityKey($entity);

        return $this->tagAwareCache->get($entityKey . '_' . $key, function (ItemInterface $item) use ($callback, $entity, $entityKey) {
            $context = new CacheContext();
            // get results early; we need Context for extra information like watched entities, expire time etc...
            $results = $callback($context, $entity);

            $item->expiresAfter($context->getExpiresAfter());
            // if entity gets removed, this will remove all related tags
            $item->tag($entityKey);

            foreach ($context->getTaggedEntities() as $taggedEntity) {
                $entityKey = $this->generateEntityKey($taggedEntity);
                $item->tag($entityKey);
            }

            return $results;
        });
    }

    public function invalidate(CacheableInterface $entity): void
    {
        $key = $this->generateEntityKey($entity);
        $this->tagAwareCache->invalidateTags([$key]);
    }

    /**
     * Returns cache key like AppEntityCustomer-9e48a586-95f7-47c2-bea7-321ce52f84da
     */
    private function generateEntityKey(CacheableInterface $taggedEntity): string
    {
        $className = ClassUtils::getClass($taggedEntity); // remove Proxy from name
        $className = str_replace('\\', '', $className); // remove reserved characters like `\`
        $id = $taggedEntity->getBaseName();

        return sprintf('%s-%s', $className, $id);
    }
}
