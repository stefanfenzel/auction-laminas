<?php

declare(strict_types=1);

namespace Auction\Infrastructure\Auctions\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

final class DoctrineAuctionRepositoryFactory implements FactoryInterface
{
    public function __invoke(
        ContainerInterface $container,
        $requestedName,
        ?array $options = null
    ): DoctrineAuctionRepository {
        return new DoctrineAuctionRepository(
            $container->get(EntityManagerInterface::class),
        );
    }
}
