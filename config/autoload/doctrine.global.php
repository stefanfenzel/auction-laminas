<?php

declare(strict_types=1);

use Doctrine\DBAL\Driver\PDO\MySQL\Driver;
use Doctrine\Persistence\Mapping\Driver\MappingDriverChain;

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                ],
            ],
        ],
        'connection' => [
            'orm_default' => [
                'params' => [
                    'driver_class' => Driver::class,
                    'dbname' => 'auction',
                    'host' => '',
                    'user' => '',
                    'password' => '',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'proxy_dir' => '/tmp/cache/DoctrineEntityProxy',
            ],
        ],
    ],
];
