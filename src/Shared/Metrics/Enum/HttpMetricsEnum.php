<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum;

enum HttpMetricsEnum: string
{
    case HttpRequestDurationMs = 'http_request_duration_ms';
    case HttpRequestCount = 'http_request_count';
    case HttpErrorCount = 'http_error_count';
}
