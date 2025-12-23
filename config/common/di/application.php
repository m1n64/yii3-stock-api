<?php

declare(strict_types=1);

use App\Domain\Repository\CityRepositoryInterface;
use App\Domain\Repository\StockRepositoryInterface;
use App\Infrastructure\CommandBus;
use App\Infrastructure\Repository\CycleCityRepository;
use App\Infrastructure\Repository\CycleStockRepository;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\ApplicationParams;
use App\Shared\Http\Middlewares\AuthMiddleware;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\DatabaseProviderInterface;
use Predis\Client;
use Predis\ClientInterface;
use Psr\SimpleCache\CacheInterface as PsrCacheInterface;
use Yiisoft\Cache\Cache;
use Yiisoft\Cache\CacheInterface;
use Yiisoft\Cache\Redis\RedisCache;
use Yiisoft\Definitions\Reference;

/** @var array $params */

return [
    ApplicationParams::class => [
        '__construct()' => [
            'name' => $params['application']['name'],
            'version' => $params['application']['version'],
        ],
    ],
    ClientInterface::class => [
        'class' => Client::class,
        '__construct()' => [
            [
                'scheme' => 'tcp',
                'host'   => getenv('REDIS_HOST'),
                'port'   => getenv('REDIS_PORT'),
            ],
        ],
    ],
    RedisCache::class => [
        '__construct()' => [
            'client' => Reference::to(ClientInterface::class),
        ],
    ],
    PsrCacheInterface::class => RedisCache::class,
    CacheInterface::class => Cache::class,
    Cache::class => [
        '__construct()' => [
            'handler' => Reference::to(RedisCache::class),
        ],
    ],
    DatabaseInterface::class => static function (DatabaseProviderInterface $dbal) {
        return $dbal->database();
    },
    AuthMiddleware::class => [
        '__construct()' => [
            'secretKey' => $params['authorization']['token'],
        ],
    ],
    CommandBusInterface::class => CommandBus::class,
    CityRepositoryInterface::class => CycleCityRepository::class,
    StockRepositoryInterface::class => CycleStockRepository::class,
];
