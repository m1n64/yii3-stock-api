<?php

declare(strict_types=1);

use App\Shared\Logger\OpenObserveFormatter;
use App\Shared\Logger\OpenObserveMonologHandler;
use App\Shared\Logger\TraceProcessor;
use App\Shared\Logger\TraceStorage;
use App\Shared\Metrics\MetricsClient;
use App\Shared\Metrics\MetricsClientInterface;
use Monolog\Formatter\JsonFormatter;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;
use Yiisoft\Definitions\Reference;
use Yiisoft\Definitions\ReferencesArray;
use Yiisoft\Log\Logger;
use Yiisoft\Log\PsrTarget;
use Yiisoft\Log\StreamTarget;
use Yiisoft\Log\Target\File\FileTarget;

/** @var array $params */

return [
    MetricsClientInterface::class => [
        'class' => MetricsClient::class,
        '__construct()' => [
            'host' => getenv('TELEGRAF_HOST'),
            'port' => (int) getenv('TELEGRAF_UDP_PORT'),
        ],
    ],

    TraceStorage::class => TraceStorage::class,
    TraceProcessor::class => [
        'class' => TraceProcessor::class,
        '__construct()' => [
            'traceStorage' => Reference::to(TraceStorage::class),
            'source' => $params['source'],
        ],
    ],

    \Monolog\Logger::class => static function (TraceProcessor $processor, OpenObserveFormatter $formatter) {
        $host = getenv('VECTOR_HOST');
        $port = (int) getenv('VECTOR_PORT');

        $handler = new OpenObserveMonologHandler($host, $port);
        $handler->setFormatter($formatter);

        return new \Monolog\Logger('stock-api-logger', [$handler], [$processor]);
    },

    PsrTarget::class => [
        'class' => PsrTarget::class,
        '__construct()' => [
            'logger' => Reference::to(Monolog\Logger::class),
        ],
        'setExportInterval()' => [1],
    ],

    LoggerInterface::class => [
        'class' => Logger::class,
        '__construct()' => [
            'targets' => ReferencesArray::from([
                PsrTarget::class,
                FileTarget::class,
                StreamTarget::class,
            ]),
        ],
    ],
];
