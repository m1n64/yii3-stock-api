<?php
declare(strict_types=1);

namespace App\Application\Service\Cache;

use App\Application\Service\Cache\Key\CityKeyGenerator;
use App\Application\Service\Cache\Key\NearbyKeyGenerator;
use App\Application\Service\Cache\Key\StockKeyGenerator;

final readonly class CacheKeyRegistry
{
    /**
     * @param CityKeyGenerator $cityKeyGenerator
     * @param StockKeyGenerator $stockKeyGenerator
     * @param NearbyKeyGenerator $nearbyKeyGenerator
     */
    public function __construct(
        private CityKeyGenerator $cityKeyGenerator,
        private StockKeyGenerator $stockKeyGenerator,
        private NearbyKeyGenerator $nearbyKeyGenerator,
    )
    {
    }

    /**
     * @param string $cityId
     * @return string
     */
    public function getCityKey(string $cityId): string
    {
        return $this->cityKeyGenerator->generateForGet($cityId);
    }

    /**
     * @param string $stockId
     * @return string
     */
    public function getStockKey(string $stockId): string
    {
        return $this->stockKeyGenerator->generateForGet($stockId);
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return string
     */
    public function getNearbyKey(float $lat, float $lng): string
    {
        return $this->nearbyKeyGenerator->generate($lat, $lng);
    }
}
