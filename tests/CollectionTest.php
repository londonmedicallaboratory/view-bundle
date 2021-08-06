<?php

declare(strict_types=1);

namespace LML\View\Tests;

use LML\View\ViewFactory\ViewFactoryCollection;
use LML\View\Tests\Fixture\ProductViewFactoryFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CollectionTest extends KernelTestCase
{
    public function testIsInitialized(): void
    {
        self::bootKernel();
        return;

        /** @var ViewFactoryCollection $collection */
        $collection = self::$kernel->getContainer()->get('lml_view.view_factory_collection');

        $viewFactory = $collection->get(ProductViewFactoryFixture::class);
        self::assertInstanceOf(ProductViewFactoryFixture::class, $viewFactory);
    }
}
