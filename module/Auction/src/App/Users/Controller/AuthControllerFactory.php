<?php

declare(strict_types=1);

namespace Auction\App\Users\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

final class AuthControllerFactory
{
    public function __invoke(ContainerInterface $container): AuthController
    {
        return new AuthController(
            $container->get(EntityManager::class),
        );
    }
}
