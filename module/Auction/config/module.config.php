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
            'auctions' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/auctions',
                    'defaults' => [
                        'controller' => AuctionsController::class,
                        'action'     => 'auctions',
                    ],
                ],
            ],
            'auctions_create' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/auctions/create',
                    'defaults' => [
                        'controller' => AuctionsController::class,
                        'action'     => 'create',
                    ],
                ],
            ],
            'logout' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
            'register' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => AuthController::class,
                        'action'     => 'register',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            '/auction' => __DIR__ . '/../view/auction/home.phtml',
        ],
        'template_path_stack' => [
            'auction' => __DIR__ . '/../view',
        ],
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
