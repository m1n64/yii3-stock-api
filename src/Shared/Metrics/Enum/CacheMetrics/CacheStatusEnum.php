<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum\CacheMetrics;

enum CacheStatusEnum: string
{
    case HIT = 'hit';
    case MISS = 'miss';
}
