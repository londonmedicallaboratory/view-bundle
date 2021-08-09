<?php

declare(strict_types=1);

namespace LML\View\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use LML\View\ViewFactory\ViewFactoryInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;

class LMLViewExtension extends ConfigurableExtension
{
    use PriorityTaggedServiceTrait;

    public function getAlias(): string
    {
        return 'lml_view';
    }

    /**
     * @param array{cache_pool: string} $mergedConfig
     *
     * @psalm-suppress MoreSpecificImplementedParamType
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->registerViewFactories($container);
        $this->registerViewCollection($container);

        // assign pool to cache wrapper
        $poolName = $mergedConfig['cache_pool'];
        $container->getDefinition('lml_view.cache_wrapper')
            ->setArgument(0, new Reference($poolName));
    }

    private function registerViewFactories(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(ViewFactoryInterface::class)->addTag('lml_view.factory')
            ->addMethodCall('setViewFactoriesCollection', [new Reference('lml_view.view_factory_collection')])
            ->addMethodCall('setCacheWrapper', [new Reference('lml_view.cache_wrapper')]);
    }

    private function registerViewCollection(ContainerBuilder $container): void
    {
        $container->getDefinition('lml_view.view_factory_collection')
            ->setArgument(0, new ServiceLocatorArgument(new TaggedIteratorArgument('lml_view.factory', null, 'default', true)));
    }

}
