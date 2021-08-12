<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\ViewFactory;

use LML\View\Tests\Fixture\Entity\User;
use LML\View\Tests\Fixture\View\UserView;
use LML\View\ViewFactory\AbstractViewFactory;

/**
 * @extends AbstractViewFactory<User, UserView, array, array>
 *
 * @see User
 * @see UserView
 */
class UserViewFactory extends AbstractViewFactory
{
    protected function one($entity, $options, $optimizer)
    {
        return new UserView(
            id: $entity->getId(),
            name: 'John Doe',
        );
    }

}
