<?php

declare(strict_types=1);

namespace LML\View\Tests;

use LML\View\ViewFactory\ViewFactoryCollection;
use LML\View\Tests\Fixture\View\ProductFixtureView;
use LML\View\Tests\Fixture\View\CategoryFixtureView;
use LML\View\Tests\Fixture\Entity\ProductFixtureEntity;
use LML\View\Tests\Fixture\ViewFactory\ProductViewFactoryFixture;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingDependency
 */
class CollectionTest extends KernelTestCase
{
    public function testBuildOne(): void
    {
        self::bootKernel();

        /** @var ProductViewFactoryFixture $viewFactory */
        $viewFactory = self::getContainer()->get(ProductViewFactoryFixture::class);
        self::assertInstanceOf(ProductViewFactoryFixture::class, $viewFactory);

        $entity = new ProductFixtureEntity('Test', 42);
        /** @var ProductFixtureView $view */
        $view = $viewFactory->buildOne($entity);
        self::assertInstanceOf(ProductFixtureView::class, $view);

        $category = $view->getCategory();
        self::assertInstanceOf(CategoryFixtureView::class, $category);
    }

    public function testCollectionInitialization(): void
    {
        self::bootKernel();

        /** @var ViewFactoryCollection $collection */
        $collection = self::$kernel->getContainer()->get('lml_view.view_factory_collection');
        self::assertInstanceOf(ViewFactoryCollection::class, $collection);
    }
}
