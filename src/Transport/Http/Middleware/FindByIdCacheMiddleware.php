<?php
declare(strict_types=1);

namespace App\Transport\Http\Middleware;

use App\Shared\Http\Middlewares\AbstractCacheMiddleware;
use Psr\Http\Message\ServerRequestInterface;

final class FindByIdCacheMiddleware extends AbstractCacheMiddleware
{
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
        $name = $this->route->getName() ?? 'unknown-route';

        $raw = sprintf('%s-%s', $name, $id);

        return 'find-by-id-cache-' . sha1($raw);
    }
}
