<?php
declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\CityService;
use App\Domain\Entity\City;
use App\Domain\Repository\CityRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;
use Codeception\Test\Unit;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class CityServiceTest extends Unit
{
    protected CityRepositoryInterface|MockObject $repository;
    protected LoggerInterface|MockObject $logger;
    protected CityService $service;

    protected function _before(): void
    {
        $this->repository = $this->createMock(CityRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->repository->method('transaction')->willReturnCallback(function ($callback) {
            return $callback();
        });

        $this->service = new CityService($this->repository, $this->logger);
    }

    public function testGetAllCities(): void
    {
        $page = 1;
        $limit = 10;
        $cities = [new City('Moscow'), new City('London')];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->with($limit, 0)
            ->willReturn($cities);

        $this->repository->expects($this->once())
            ->method('count')
            ->willReturn(2);

        $result = $this->service->getAllCities($page, $limit);

        $this->assertInstanceOf(PaginatedResult::class, $result);
        $this->assertCount(2, $result->items);
        $this->assertEquals(2, $result->total);
    }

    public function testGetCitySuccess(): void
    {
        $cityId = 'uuid-123';
        $city = new City('New York');

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($cityId)
            ->willReturn($city);

        $result = $this->service->getCity($cityId);

        $this->assertSame($city, $result);
    }

    public function testGetCityNotFound(): void
    {
        $cityId = 'non-existent';

        $this->repository->method('findById')->willReturn(null);

        $this->logger->expects($this->once())
            ->method('warning')
            ->with($this->stringContains('not found'));

        $result = $this->service->getCity($cityId);

        $this->assertNull($result);
    }

    public function testUpdateCityThrowsExceptionWhenNotFound(): void
    {
        $cityId = 'unknown';
        $this->repository->method('findById')->with($cityId)->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("City with ID $cityId not found");

        $this->service->update($cityId, 'New Name');
    }

    public function testDeleteCitySuccess(): void
    {
        $cityId = '123';
        $city = new City('London');

        $this->repository->expects($this->once())
            ->method('findById')
            ->with($cityId)
            ->willReturn($city);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($city);

        $this->service->delete($cityId);
    }

    public function testDeleteCityThrowsExceptionWhenNotFound(): void
    {
        $cityId = 'missing';
        $this->repository->method('findById')->with($cityId)->willReturn(null);

        $this->expectException(InvalidArgumentException::class);

        $this->service->delete($cityId);
    }
}
