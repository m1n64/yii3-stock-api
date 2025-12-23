<?php
declare(strict_types=1);

namespace App\Shared\Logger;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class OpenObserveFormatter extends JsonFormatter
{
    /**
     * @param LogRecord $record
     * @return string
     */
    public function format(LogRecord $record): string
    {
        $data = $record->toArray();

        if (isset($data['extra']['trace_id'])) {
            $data['trace_id'] = $data['extra']['trace_id'];
        }
        if (isset($data['extra']['span_id'])) {
            $data['span_id'] = $data['extra']['span_id'];
        }
        if (isset($data['extra']['source'])) {
            $data['source'] = $data['extra']['source'];
        }

        $levelName = $record->level->name;
        $traceId = $record->extra['trace_id'] ?? 'no-trace';

        $method = $_SERVER['REQUEST_METHOD'] ?? 'CLI';
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        $endpoint = "$method $uri";

        $data['message'] = sprintf(
            '[%s] [%s] [%s] %s',
            $levelName,
            $traceId,
            $endpoint,
            $record->message
        );

        unset($data['extra']['trace_id'], $data['extra']['span_id'], $data['extra']['source']);

        return $this->toJson($data) . ($this->appendNewline ? "\n" : '');
    }
}
