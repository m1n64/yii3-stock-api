<?php
declare(strict_types=1);

namespace App\Application\Listener\Stock;

use App\Application\Service\Cache\CacheInvalidationService;
use App\Domain\Event\Stock\StockChanged;
use Psr\SimpleCache\InvalidArgumentException;

final class InvalidateNearbyCacheListener
{
    /**
     * @param CacheInvalidationService $cacheService
     */
    public function __construct(
        private CacheInvalidationService $cacheService,
    )
    {
    }

    /**
     * @param StockChanged $event
     * @return void
     * @throws InvalidArgumentException
     */
    public function __invoke(StockChanged $event): void
    {
        $this->cacheService->invalidateNearby($event->lat, $event->lng);
    }
}
