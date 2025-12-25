<?php
declare(strict_types=1);

namespace App\Transport\Http\Middleware;

use App\Application\Service\Cache\CacheKeyRegistry;
use App\Shared\Http\Middlewares\AbstractCacheMiddleware;
use App\Shared\Metrics\MetricsClientInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\CurrentRoute;

final class FindNearestCacheMiddleware extends AbstractCacheMiddleware
{
    /**
     * @param CurrentRoute $route
     * @param CacheInterface $cache
     * @param DataResponseFactoryInterface $responseFactory
     * @param LoggerInterface $logger
     * @param MetricsClientInterface $metricsClient
     * @param CacheKeyRegistry $cacheKeyRegistry
     */
    public function __construct(
        CurrentRoute $route,
        CacheInterface $cache,
        DataResponseFactoryInterface $responseFactory,
        LoggerInterface $logger,
        MetricsClientInterface $metricsClient,
        private CacheKeyRegistry $cacheKeyRegistry,
    )
    {
        parent::__construct($route, $cache, $responseFactory, $logger, $metricsClient);
    }

    /**
     * @return int
     */
    protected function getTtl(): int
    {
        return 60 * 15; // 15 m
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    protected function getCacheKey(ServerRequestInterface $request): string
    {
        $params = $request->getParsedBody();

        $lat = (float) ($params['lat'] ?? 0.0);
        $lng = (float) ($params['lng'] ?? 0.0);

        return $this->cacheKeyRegistry->getNearbyKey($lat, $lng);
    }
}
