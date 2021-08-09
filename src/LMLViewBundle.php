<?php

declare(strict_types=1);

namespace LML\View;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use LML\View\DependencyInjection\LMLViewExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

class LMLViewBundle extends Bundle
{
    public function getContainerExtension(): ExtensionInterface
    {
        return new LMLViewExtension();
    }
}
