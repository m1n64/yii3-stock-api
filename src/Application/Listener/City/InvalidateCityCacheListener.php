<?php
declare(strict_types=1);

namespace App\Application\Listener\City;

use App\Application\Service\Cache\CacheInvalidationService;
use App\Domain\Event\City\CityChanged;
use Psr\SimpleCache\InvalidArgumentException;

final class InvalidateCityCacheListener
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
     * @param CityChanged $event
     * @return void
     * @throws InvalidArgumentException
     */
    public function __invoke(CityChanged $event): void
    {
        $this->cacheService->invalidateCity($event->cityId);
    }
}
