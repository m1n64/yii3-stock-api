<?php
declare(strict_types=1);

namespace App\Transport\Http\Middleware;

use App\Shared\Http\Middlewares\AbstractCacheMiddleware;
use Psr\Http\Message\ServerRequestInterface;

final class FindNearestCacheMiddleware extends AbstractCacheMiddleware
{
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

        $lat = round((float) $params['lat'], 4);
        $lng = round((float) $params['lng'], 4);

        $raw = sprintf('%s-%s-%s', $request->getUri()->getPath(), $lat, $lng);

        return 'nearest-cache-' . sha1($raw);
    }
}
