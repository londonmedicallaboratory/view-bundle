<?php

declare(strict_types=1);

namespace LML\View\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use LML\View\ViewFactory\ViewFactoryInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;

class LMLViewExtension extends ConfigurableExtension implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    public function getAlias(): string
    {
        return 'lml_view';
    }

    public function process(ContainerBuilder $container): void
    {
        $collection = $container->getDefinition('lml_view.view_factory_collection');
        $collection->setArgument(0, new ServiceLocatorArgument(new TaggedIteratorArgument('lml_view.factory', null, 'default', true)));


    }

    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {

//        $cache = $container->getDefinition('Symfony\\Contracts\\Cache\\TagAwareCacheInterface $fixturewrtertwerterwtes');

        $container->registerForAutoconfiguration(ViewFactoryInterface::class)->addTag('lml_view.factory')
            ->addMethodCall('setViewFactoriesCollection', [new Reference('lml_view.view_factory_collection')])
            ->addMethodCall('setCacheWrapper', [new Reference('lml_view.cache_wrapper')]);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');

        $this->configureTestEnvironment($container);
    }

    /**
     * Make all services public when env=test
     */
    private function configureTestEnvironment(ContainerBuilder $container): void
    {
        if ($container->getParameter('kernel.environment') !== 'test') {
            return;
        }
        foreach ($container->getDefinitions() as $id => $definition) {
            if (str_starts_with((string)$id, 'lml_view.')) {
                $definition->setPublic(true);
            }
        }
    }
}
