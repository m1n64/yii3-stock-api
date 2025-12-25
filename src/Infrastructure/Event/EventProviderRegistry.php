<?php
declare(strict_types=1);

namespace App\Infrastructure\Event;

use App\Application\Listener\City\InvalidateCityCacheListener;
use App\Application\Listener\Stock\InvalidateStockCacheListener;
use App\Application\Listener\Stock\InvalidateNearbyCacheListener;
use App\Domain\Event\City\CityChanged;
use App\Domain\Event\Stock\StockChanged;

final class EventProviderRegistry
{
    /**
     * @var array<class-string, list<class-string>>
     */
    public const array EVENTS = [
        CityChanged::class => [
            InvalidateCityCacheListener::class,
        ],
        StockChanged::class => [
            InvalidateStockCacheListener::class,
            InvalidateNearbyCacheListener::class,
        ],
    ];
}
