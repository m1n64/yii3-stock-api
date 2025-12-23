<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum;

enum CacheMetricsEnum: string
{
    case HTTP_CACHE_HITS = 'http_cache_hits';
}
