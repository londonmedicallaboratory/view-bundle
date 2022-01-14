<?php

declare(strict_types=1);

namespace LML\View\Tests;

use LML\View\ViewFactory\ViewFactoryCollection;
use LML\View\Tests\Fixture\View\UserView;
use LML\View\Tests\Fixture\View\VideoView;
use LML\View\Tests\Fixture\Entity\User;
use LML\View\Tests\Fixture\ViewFactory\UserViewFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @psalm-suppress MissingDependency
 */
class CollectionTest extends KernelTestCase
{
    public function testBuildOne(): void
    {
        self::bootKernel();

        /** @var UserViewFactory $viewFactory */
        $viewFactory = self::getContainer()->get(UserViewFactory::class);
        self::assertInstanceOf(UserViewFactory::class, $viewFactory);

        $entity = new User('John', 'Connor', 42);
        /** @var UserView $view */
        $view = $viewFactory->buildOne($entity);
        self::assertInstanceOf(UserView::class, $view);

        $category = $view->getCategory();
        self::assertInstanceOf(VideoView::class, $category);
    }

    public function testCollectionInitialization(): void
    {
        self::bootKernel();

        /** @var ViewFactoryCollection $collection */
        $collection = self::$kernel->getContainer()->get('lml_view.view_factory_collection');
        self::assertInstanceOf(ViewFactoryCollection::class, $collection);
    }
}
