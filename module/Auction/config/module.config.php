<?php

declare(strict_types=1);

namespace Auction;

use Auction\App\Auctions\Controller\AuctionsController;
use Auction\App\Users\Controller\AuthController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use Laminas\ServiceManager\Factory\InvokableFactory;

return [
    'controllers' => [
        'factories' => [
            AuctionsController::class => InvokableFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'album' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/auction[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}',
                    ],
                    'defaults' => [
                        'controller' => AuctionsController::class,
                        'action'     => 'index',
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
    ],
    'service_manager' => [
        'factories' => [
            // add factories here
        ],
    ],
];
