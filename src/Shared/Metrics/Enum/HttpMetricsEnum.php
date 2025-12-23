<?php
declare(strict_types=1);

namespace App\Shared\Metrics\Enum;

enum HttpMetricsEnum: string
{
    case HTTP_REQUEST_DURATION = 'http_request_duration_ms';
    case HTTP_REQUEST_COUNT = 'http_request_count';
    case HTTP_ERROR_COUNT = 'http_error_count';
}
