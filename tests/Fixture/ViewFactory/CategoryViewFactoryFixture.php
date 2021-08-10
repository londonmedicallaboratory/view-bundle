<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\ViewFactory;

use LML\View\Lazy\LazyValue;
use LML\View\ViewFactory\AbstractViewFactory;
use LML\View\Tests\Fixture\View\CategoryFixtureView;
use LML\View\Tests\Fixture\Entity\CategoryFixtureEntity;

/**
 * @extends AbstractViewFactory<CategoryFixtureEntity, CategoryFixtureView, array, array>
 *
 * @see CategoryFixtureEntity
 * @see CategoryFixtureView
 */
class CategoryViewFactoryFixture extends AbstractViewFactory
{
    protected function one($entity, $options, LazyValue $optimizer): CategoryFixtureView
    {
        return new CategoryFixtureView();
    }
}
