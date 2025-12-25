<?php
declare(strict_types=1);

namespace App\Transport\Http\Middleware;

use App\Application\Service\Cache\CacheKeyRegistry;
use App\Domain\Enum\EntityTypeEnum;
use App\Shared\Http\Middlewares\AbstractCacheMiddleware;
use App\Shared\Metrics\MetricsClientInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Router\CurrentRoute;

final class FindByIdCacheMiddleware extends AbstractCacheMiddleware
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
        return 60 * 60; // 1 hour
    }

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    protected function getCacheKey(ServerRequestInterface $request): string
    {
        $id = $this->route->getArgument('id');
        $entityType = EntityTypeEnum::from($this->route->getArgument('entity'));

        return match ($entityType) {
            EntityTypeEnum::City => $this->cacheKeyRegistry->getCityKey($id),
            EntityTypeEnum::Stock => $this->cacheKeyRegistry->getStockKey($id),
        };
    }
}
