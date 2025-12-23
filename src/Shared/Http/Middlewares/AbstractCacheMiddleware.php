<?php
declare(strict_types=1);

namespace App\Shared\Http\Middlewares;

use App\Shared\Http\ResponseFactory;
use App\Shared\Metrics\Enum\CacheMetrics\CacheStatusEnum;
use App\Shared\Metrics\Enum\CacheMetrics\MetricsLabelEnum;
use App\Shared\Metrics\Enum\CacheMetricsEnum;
use App\Shared\Metrics\MetricsClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Psr\SimpleCache\CacheInterface;
use Yiisoft\DataResponse\DataResponseFactoryInterface;
use Yiisoft\Http\Status;
use Yiisoft\Router\CurrentRoute;

abstract class AbstractCacheMiddleware implements MiddlewareInterface
{
    /**
     * @param CacheInterface $cache
     * @param DataResponseFactoryInterface $responseFactory
     * @param LoggerInterface $logger
     * @param MetricsClientInterface $metricsClient
     * @param CurrentRoute $route
     */
    public function __construct(
        protected CurrentRoute $route,
        private CacheInterface $cache,
        private DataResponseFactoryInterface $responseFactory,
        private LoggerInterface $logger,
        private MetricsClientInterface $metricsClient,
    )
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $cacheKey = $this->getCacheKey($request);

        $cachedResponse = $this->cache->get($cacheKey);
        if (is_array($cachedResponse)) {
            $response = $this->responseFactory->createResponse($cachedResponse['body'], $cachedResponse['status']);

            foreach ($cachedResponse['headers'] as $name => $value) {
                $response = $response->withHeader($name, $value);
            }

            $this->logger->info("Cache hit for key: {$cacheKey}, returning cached response.");
            $this->sendCacheMetrics(CacheStatusEnum::HIT);

            // Maybe Sliding Expiration from parameter, but we need to invalidate cache...

            return $response->withHeader('X-Cache', 'HIT')->withHeader('Cache-Control', 'public, max-age=' . $this->getTtl());
        }

        $response = $handler->handle($request);

        if ($response->getStatusCode() === Status::OK) {
            $body = $response->getData();

            $this->cache->set($cacheKey, [
                'status' => $response->getStatusCode(),
                'body' => $body,
                'headers' => $response->getHeaders(),
            ], $this->getTtl());
        }

        $this->sendCacheMetrics(CacheStatusEnum::MISS);

        return $response->withHeader('X-Cache', 'MISS')->withHeader('Cache-Control', 'public, max-age=' . $this->getTtl());
    }

    /**
     * @return int
     */
    abstract protected function getTtl(): int;

    /**
     * @param ServerRequestInterface $request
     * @return string
     */
    abstract protected function getCacheKey(ServerRequestInterface $request): string;

    /**
     * @param CacheStatusEnum $status
     * @return void
     */
    private function sendCacheMetrics(CacheStatusEnum $status): void
    {
        $this->metricsClient->increment(
            CacheMetricsEnum::HTTP_CACHE_HITS,
            1,
            [
                MetricsLabelEnum::STATUS->value => $status,
                MetricsLabelEnum::ROUTE->value => $this->route->getName() ?? 'unknown',
            ]
        );
    }
}
