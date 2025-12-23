<?php
declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\StockService;
use App\Domain\Entity\City;
use App\Domain\Entity\Stock;
use App\Domain\Repository\CityRepositoryInterface;
use App\Domain\Repository\StockRepositoryInterface;
use Codeception\Test\Unit;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Log\LoggerInterface;

class StockServiceTest extends Unit
{
    protected StockRepositoryInterface|MockObject $repository;
    protected CityRepositoryInterface|MockObject $cityRepository;
    protected LoggerInterface|MockObject $logger;
    protected StockService $service;

    protected function _before(): void
    {
        parent::_before();

        $this->repository = $this->createMock(StockRepositoryInterface::class);
        $this->cityRepository = $this->createMock(CityRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->repository->method('transaction')->willReturnCallback(static function (callable $callback): mixed {
            return $callback();
        });

        $this->service = new StockService(
            $this->repository,
            $this->cityRepository,
            $this->logger
        );
    }

    public function testCreateStockSuccess(): void
    {
        $cityId = 'city-uuid';
        $city = new City('Almaty');

        $this->cityRepository->expects($this->once())
            ->method('findById')
            ->with($cityId)
            ->willReturn($city);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Stock::class));

        $stock = $this->service->create($cityId, 'Abaya 10', 43.2, 76.9);

        $this->assertInstanceOf(Stock::class, $stock);
        $this->assertEquals('Abaya 10', $stock->getAddress());
    }

    public function testUpdateStockCoordinatesFailure(): void
    {
        $stockId = 'stock-uuid';
        $city = new City('Minks');
        $stock = new Stock($city, 'Old Address', 1.0, 1.0);

        $this->repository->method('getById')->with($stockId)->willReturn($stock);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Both coordinates are required for an update.");

        $this->service->update($stockId, null, null, 50.0, null);
    }

    public function testGetStockNotFoundReturnsNull(): void
    {
        $id = 'absent-id';
        $this->repository->method('getById')->with($id)->willReturn(null);

        $this->logger->expects($this->once())->method('warning');

        $result = $this->service->getStock($id);
        $this->assertNull($result);
    }

    public function testDeleteStockSuccess(): void
    {
        $id = 'id-to-delete';
        $stock = $this->createMock(Stock::class);

        $this->repository->method('getById')->with($id)->willReturn($stock);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($stock);

        $this->service->delete($id);
    }
}
