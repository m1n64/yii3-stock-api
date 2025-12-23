<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum\CacheMetrics;

enum MetricsLabelEnum: string
{
    case STATUS = 'status';
    case ROUTE = 'route';
}
