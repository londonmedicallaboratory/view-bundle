<?php

declare(strict_types=1);

namespace LML\View;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use LML\View\DependencyInjection\LMLViewExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Argument\ServiceLocatorArgument;
use Symfony\Component\DependencyInjection\Argument\TaggedIteratorArgument;

class LMLViewBundle extends Bundle implements CompilerPassInterface
{
    public function getContainerExtension(): ExtensionInterface
    {
        return $this->extension ??= new LMLViewExtension();
    }

    public function build(ContainerBuilder $container)
    {
//        $container->addCompilerPass($this, PassConfig::TYPE_OPTIMIZE);
    }


    public function process(ContainerBuilder $container): void
    {
        //        $cache = $container->getDefinition('fixturewrtertwerterwtes.inner');
        $cache = $container->getAlias('fixturewrtertwerterwtes.cache');

        $collection = $container->getDefinition('lml_view.view_factory_collection');
        $repositories = $container->findTaggedServiceIds('lml_view.factory');
        dd($repositories);
        foreach ($repositories as $repository) {
            dump($repository);
        }
        $collection->setArgument(0, new ServiceLocatorArgument(new TaggedIteratorArgument('lml_view.factory', null, 'default', true)));
    }
}
