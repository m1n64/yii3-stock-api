<?php
declare(strict_types=1);

namespace App\Tests\Application\Service\Cache;

use App\Application\Service\Cache\CacheInvalidationService;
use App\Application\Service\Cache\CacheKeyRegistry;
use App\Application\Service\Cache\Key\CityKeyGenerator;
use App\Application\Service\Cache\Key\NearbyKeyGenerator;
use App\Application\Service\Cache\Key\StockKeyGenerator;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\SimpleCache\CacheInterface;

class CacheInvalidationServiceTest extends Unit
{
    private CacheInterface|MockObject $cache;
    private CacheInvalidationService $service;

    protected function _before(): void
    {
        $this->cache = $this->createMock(CacheInterface::class);

        $registry = new CacheKeyRegistry(
            new CityKeyGenerator(),
            new StockKeyGenerator(),
            new NearbyKeyGenerator(),
        );

        $this->service = new CacheInvalidationService(
            $this->cache,
            $registry,
        );
    }

    public function testInvalidateCityRemovesCacheKey(): void
    {
        $cityId = 'city-1';
        $expectedKey = new CityKeyGenerator()->generateForGet($cityId);

        $this->cache
            ->expects($this->once())
            ->method('delete')
            ->with($expectedKey);

        $this->service->invalidateCity($cityId);
    }

    public function testInvalidateStockRemovesCacheKey(): void
    {
        $stockId = 'stock-42';
        $expectedKey = new StockKeyGenerator()->generateForGet($stockId);

        $this->cache
            ->expects($this->once())
            ->method('delete')
            ->with($expectedKey);

        $this->service->invalidateStock($stockId);
    }

    public function testInvalidateNearbyRemovesCacheKey(): void
    {
        $lat = 52.5200;
        $lng = 13.4050;

        $expectedKey = new NearbyKeyGenerator()->generate($lat, $lng);

        $this->cache
            ->expects($this->once())
            ->method('delete')
            ->with($expectedKey);

        $this->service->invalidateNearby($lat, $lng);
    }
}
