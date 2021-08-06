<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture;

use LML\View\ViewFactory\AbstractViewFactory;

class ProductViewFactoryFixture extends AbstractViewFactory
{
    protected function one($entity, $options)
    {
        throw new \RuntimeException('N/A');
    }
}
