<?php
declare(strict_types=1);

namespace App\Tests\Application\Service;

use App\Application\Service\StockService;
use App\Domain\Entity\City;
use App\Domain\Entity\Stock;
use App\Domain\Event\Stock\StockChanged;
use App\Domain\Repository\CityRepositoryInterface;
use App\Domain\Repository\StockRepositoryInterface;
use Codeception\Test\Unit;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;
use ReflectionClass;

class StockServiceTest extends Unit
{
    protected StockRepositoryInterface|MockObject $repository;
    protected CityRepositoryInterface|MockObject $cityRepository;
    protected LoggerInterface|MockObject $logger;
    protected EventDispatcherInterface|MockObject $eventDispatcher;
    protected StockService $service;

    protected function _before(): void
    {
        parent::_before();

        $this->repository = $this->createMock(StockRepositoryInterface::class);
        $this->cityRepository = $this->createMock(CityRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->repository->method('transaction')
            ->willReturnCallback(static function (callable $callback): mixed {
                try {
                    return $callback();
                } catch (InvalidArgumentException|\Throwable $e) {
                    throw $e;
                }
            });

        $this->eventDispatcher
            ->method('dispatch')
            ->willReturnCallback(fn ($event) => $event);

        $this->service = new StockService(
            $this->repository,
            $this->cityRepository,
            $this->logger,
            $this->eventDispatcher,
        );
    }

    public function testCreateStockSuccess(): void
    {
        $cityId = 'city-uuid';
        $city = new City('Almaty');

        $this->cityRepository->expects($this->once())
            ->method('getById')
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
        $stock = new Stock(new City('Warshaw'), 'Str Dong', 10.0123, 20.2340);
        $this->setProperties($stock, [
            'id' => $id,
        ]);

        $this->repository->method('getById')->with($id)->willReturn($stock);

        $this->repository->expects($this->once())
            ->method('delete')
            ->with($stock);

        $this->service->delete($id);
    }

    public function testDeleteCityThrowsExceptionWhenNotFound(): void
    {
        $stockId = 'missing';
        $this->repository->method('getById')->with($stockId)->willReturn(null);

        $this->expectException(InvalidArgumentException::class);

        $this->service->delete($stockId);
    }

    private function setProperties(object $object, array $data): void
    {
        $reflection = new ReflectionClass($object);
        foreach ($data as $property => $value) {
            $prop = $reflection->getProperty($property);
            $prop->setValue($object, $value);
        }
    }
}
