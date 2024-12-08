<?php

declare(strict_types=1);

namespace Auction\App\Auctions\Controller;

use Auction\App\UuidFromRamseyFactory;
use Auction\Infrastructure\Auctions\Repository\DoctrineAuctionRepository;
use Auction\Infrastructure\Users\Repository\DoctrineUserRepository;
use Laminas\Authentication\AuthenticationService;
use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;

final class AuctionsControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): AuctionsController
    {
        return new AuctionsController(
            $container->get(UuidFromRamseyFactory::class),
            $container->get(DoctrineAuctionRepository::class),
            $container->get(DoctrineUserRepository::class),
            new AuthenticationService(),
        );
    }
}
