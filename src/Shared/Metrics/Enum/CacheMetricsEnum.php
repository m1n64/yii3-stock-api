<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum;

enum CacheMetricsEnum: string
{
    case HttpCacheHits = 'http_cache_hits';
}
