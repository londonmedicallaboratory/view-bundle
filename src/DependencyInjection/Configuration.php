<?php

declare(strict_types=1);

namespace LML\View\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('lml_sdk');
        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('cache_pool')->isRequired()->end()
            ?->end()
            ;

        return $treeBuilder;
    }
}
