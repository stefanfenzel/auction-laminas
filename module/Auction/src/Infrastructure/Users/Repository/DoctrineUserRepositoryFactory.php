<?php

declare(strict_types=1);

namespace Auction\Infrastructure\Users\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;

final class DoctrineUserRepositoryFactory
{
    public function __invoke(ContainerInterface $container): DoctrineUserRepository
    {
        return new DoctrineUserRepository(
            $container->get(EntityManagerInterface::class),
        );
    }
}
