<?php

declare(strict_types=1);

namespace LML\View\Tests\Fixture\Entity;

class LikedVideo
{
    public function __construct(private User $user, private Video $video)
    {
    }
}
