<?php

declare(strict_types=1);

namespace LML\View;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use LML\View\DependencyInjection\LMLViewExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class LMLViewBundle extends Bundle implements CompilerPassInterface
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new LMLViewExtension();
    }

    public function process(ContainerBuilder $container): void
    {
    }
}
