<?php

declare(strict_types=1);

namespace Auction;

use Auction\App\Auctions\Controller\AuctionsController;
use Auction\App\Auctions\Controller\AuctionsControllerFactory;
use Auction\App\Users\Controller\AuthController;
use Auction\App\Users\Controller\AuthControllerFactory;
use Auction\App\UuidFromRamseyFactory;
use Auction\Infrastructure\Auctions\Repository\DoctrineAuctionRepository;
use Auction\Infrastructure\Auctions\Repository\DoctrineAuctionRepositoryFactory;
use Auction\Infrastructure\Users\Repository\DoctrineUserRepository;
use Auction\Infrastructure\Users\Repository\DoctrineUserRepositoryFactory;
use Doctrine\ORM\EntityManagerInterface;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'controllers' => [
        'factories' => [
            AuctionsController::class => AuctionsControllerFactory::class,
            AuthController::class => AuthControllerFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'home' => [
                'type'    => Literal::class,
                'options' => [
                    'route' => '/home',
                    'defaults' => [
                        'controller' => AuctionsController::class,
                        'action'     => 'home',
                    ],
                ],
            ],
            'dashboard' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/dashboard',
                    'defaults' => [
                        'controller' => AuctionsController::class,
                        'action'     => 'dashboard',
                    ],
                ],
            ],
            'show' => [
                'type' => Segment::class,
                'options' => [
                    'route'    => '/show/:id',
                    'constraints' => [
                        'id' => '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}',
                    ],
                    'defaults' => [
                        'controller' => AuctionsController::class,
                        'action'     => 'show',
                    ],
                ],
            ],
            'login' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/login',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'login',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'auction' => __DIR__ . '/../view',
        ],
        'template_map' => [
            '/auction' => __DIR__ . '/../view/auction/home.phtml',
        ]
    ],
    'service_manager' => [
        'factories' => [
            DoctrineAuctionRepository::class => DoctrineAuctionRepositoryFactory::class,
            DoctrineUserRepository::class => DoctrineUserRepositoryFactory::class,
        ],
        'invokables' => [
            UuidFromRamseyFactory::class => UuidFromRamseyFactory::class,
        ],
        'aliases' => [
            EntityManagerInterface::class => 'doctrine.entitymanager.orm_default',
        ],
    ],
];
