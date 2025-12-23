<?php
declare(strict_types=1);

namespace App\Shared\Logger;

use Monolog\LogRecord;
use Monolog\Processor\ProcessorInterface;

class TraceProcessor implements ProcessorInterface
{
    /**
     * @var string
     */
    private string $spanId;

    /**
     * @throws \Random\RandomException
     */
    public function __construct(
        private TraceStorage $traceStorage,
        private string $source,
    )
    {
        $this->spanId = bin2hex(random_bytes(16));
    }

    /**
     * @param LogRecord $record
     * @return LogRecord
     */
    public function __invoke(LogRecord $record): LogRecord
    {
        $traceId = $this->traceStorage->get();

        $record->extra['trace_id'] = $traceId;
        $record->extra['span_id'] = $this->spanId;
        $record->extra['source'] = $this->source;

        return $record;
    }
}
