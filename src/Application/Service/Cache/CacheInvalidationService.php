<?php
declare(strict_types=1);

namespace App\Application\Service\Cache;

use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

readonly class CacheInvalidationService
{
    /**
     * @param CacheInterface $cache
     * @param CacheKeyRegistry $cacheKeyRegistry
     */
    public function __construct(
        private CacheInterface $cache,
        private CacheKeyRegistry $cacheKeyRegistry,
    )
    {
    }

    /**
     * @param string $cityId
     * @return void
     * @throws InvalidArgumentException
     */
    public function invalidateCity(string $cityId): void
    {
        $this->cache->delete($this->cacheKeyRegistry->getCityKey($cityId));
    }

    /**
     * @param string $stockId
     * @return void
     * @throws InvalidArgumentException
     */
    public function invalidateStock(string $stockId): void
    {
        $this->cache->delete($this->cacheKeyRegistry->getStockKey($stockId));
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return void
     * @throws InvalidArgumentException
     */
    public function invalidateNearby(float $lat, float $lng): void
    {
        $this->cache->delete($this->cacheKeyRegistry->getNearbyKey($lat, $lng));
    }
}
