<?php

declare(strict_types=1);

use Cycle\Database\Config\PDOConnectionConfig;
use Cycle\Database\Config\Postgres\DsnConnectionConfig;
use Cycle\Database\Config\PostgresDriverConfig;
use Cycle\Database\Driver\Postgres\PostgresDriver;
use Cycle\Schema\Provider\PhpFileSchemaProvider;
use Yiisoft\Yii\Cycle\Schema\Provider\FromConveyorSchemaProvider;

return [
    'application' => require __DIR__ . '/application.php',

    'source' => getenv('APP_SOURCE'),

    'yiisoft/aliases' => [
        'aliases' => require __DIR__ . '/aliases.php',
    ],
    'yiisoft/yii-cycle' => [
        'dbal' => [
            'default' => 'default',
            'aliases' => [],
            'databases' => [
                'default' => ['connection' => 'postgres'],
            ],
            'connections' => [
                'postgres' => new PostgresDriverConfig(
                    connection: new DsnConnectionConfig(
                        dsn: 'pgsql:host=' . getenv('DB_HOST') . ';port=' . getenv('DB_PORT') . ';dbname=' . getenv('DB_DATABASE'),
                        user: getenv('DB_USERNAME'),
                        password: getenv('DB_PASSWORD')
                    ),
                    driver: PostgresDriver::class,
                ),
            ],
        ],
        'migrations' => [
            'directory' => '@root/migrations',
            'namespace' => 'App\\Migration',
            'table' => 'migration',
            'safe' => false,
        ],
        'schema-providers' => [
            // Uncomment next line to enable a Schema caching in the common cache
            // \Yiisoft\Yii\Cycle\Schema\Provider\SimpleCacheSchemaProvider::class => ['key' => 'cycle-orm-cache-key'],

            // Store generated Schema in the file
            PhpFileSchemaProvider::class => [
                'mode' => PhpFileSchemaProvider::MODE_WRITE_ONLY,
                'file' => '@runtime/schema.php',
            ],

            FromConveyorSchemaProvider::class => [
                'generators' => [
                    //Cycle\Schema\Generator\SyncTables::class, // sync table changes to database
                ],
            ],
        ],
        'entity-paths' => [
            '@src',
        ],
    ],
    'authorization' => [
        'token' => getenv('BEARER_TOKEN'),
    ]
];
