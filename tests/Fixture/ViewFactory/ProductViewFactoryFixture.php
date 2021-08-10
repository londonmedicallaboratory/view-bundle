<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\ViewFactory;

use LML\View\Lazy\LazyValue;
use LML\View\ViewFactory\AbstractViewFactory;
use LML\View\Tests\Fixture\View\ProductFixtureView;
use LML\View\Tests\Fixture\View\CategoryFixtureView;
use LML\View\Tests\Fixture\Entity\ProductFixtureEntity;
use LML\View\Tests\Fixture\Entity\CategoryFixtureEntity;

/**
 * @extends AbstractViewFactory<ProductFixtureEntity, ProductFixtureView, array, array>
 *
 * @see ProductFixtureEntity
 * @see ProductFixtureView
 */
class ProductViewFactoryFixture extends AbstractViewFactory
{
    protected function one($entity, $options, LazyValue $optimizer)
    {
        return new ProductFixtureView(
            name: 'Product name',
            category: new LazyValue(fn() => $this->getCategory()),
        );
    }

    private function getCategory(): CategoryFixtureView
    {
        $categoryEntity = new CategoryFixtureEntity('Test');

        return $this->get(CategoryViewFactoryFixture::class)->buildOne($categoryEntity);
    }
}
