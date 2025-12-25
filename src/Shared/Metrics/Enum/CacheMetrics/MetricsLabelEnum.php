<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum\CacheMetrics;

enum MetricsLabelEnum: string
{
    case Status = 'status';
    case Route = 'route';
}
