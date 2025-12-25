<?php
declare(strict_types=1);

namespace App\Shared\Http\Middlewares;

use App\Shared\Metrics\Enum\HttpMetricsEnum;
use App\Shared\Metrics\MetricsClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Yiisoft\Http\Status;

final class RequestMetricsMiddleware implements MiddlewareInterface
{
    /**
     * @param LoggerInterface $logger
     * @param MetricsClientInterface $metricsClient
     */
    public function __construct(
        private LoggerInterface $logger,
        private MetricsClientInterface $metricsClient,
    )
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $start = hrtime(true);

        try {
            $response = $handler->handle($request);

            return $response;
        } finally {
            $durationMs = (hrtime(true) - $start) / 1_000_000;
            $timestampNs = (int) (microtime(true) * 1_000_000_000);

            $method = $request->getMethod();
            $path = $this->normalizePath($request->getUri()->getPath());
            $statusCode = $response?->getStatusCode() ?? Status::INTERNAL_SERVER_ERROR;

            $tags = compact('method', 'path', 'statusCode');
            $this->metricsClient->timing(HttpMetricsEnum::HttpRequestDurationMs, $durationMs, $tags, $timestampNs);
            $this->metricsClient->increment(HttpMetricsEnum::HttpRequestCount, 1, $tags, $timestampNs);

            if ($statusCode >= 500) {
                $this->metricsClient->increment(HttpMetricsEnum::HttpErrorCount, 1, ['statusCode' => $statusCode], $timestampNs);
            }

            $this->logger->info("Request (app) finished for $durationMs ms with $statusCode status code", [
                'method' => $method,
                'path' => $path,
                'statusCode' => $statusCode,
                'durationMs' => $durationMs,
            ]);
        }
    }

    /**
     * @param string $path
     * @return string
     */
    private function normalizePath(string $path): string
    {
        return preg_replace('~/[0-9a-fA-F-]{36}~', '/{id}', $path);
    }
}
