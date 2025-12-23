<?php
declare(strict_types=1);

namespace App\Tests\Transport\Http\Mapper;

use App\Domain\Entity\City;
use App\Domain\Entity\Stock;
use App\Transport\Http\Dto\StockDto;
use App\Transport\Http\Mapper\StockDtoMapper;
use Codeception\Test\Unit;
use DateTimeImmutable;
use ReflectionClass;

class StockDtoMapperTest extends Unit
{
    public function testFromEntityMapsAllFieldsCorrectly(): void
    {
        // 1. Arrange
        $city = new City('Ottawa');
        $stock = new Stock(
            city: $city,
            address: 'address',
            lat: 43.238949,
            lng: 76.889709
        );

        $stockId = 'uuid-stock-123';
        $cityId = 'uuid-city-456';
        $createdAt = new DateTimeImmutable('2025-12-21 10:00:00');
        $updatedAt = new DateTimeImmutable('2025-12-23 15:00:00');

        $this->setProperties($stock, [
            'id' => $stockId,
            'cityId' => $cityId,
            'createdAt' => $createdAt,
            'updatedAt' => $updatedAt,
        ]);

        $dto = StockDtoMapper::fromEntity($stock);

        $this->assertInstanceOf(StockDto::class, $dto);
        $this->assertSame($stockId, $dto->id);
        $this->assertSame($cityId, $dto->cityId);
        $this->assertSame('address', $dto->address);
        $this->assertSame(43.238949, $dto->lat);
        $this->assertSame(76.889709, $dto->lng);

        $this->assertSame($createdAt->format('c'), $dto->createdAt->format('c'));
        $this->assertSame($updatedAt->format('c'), $dto->updatedAt?->format('c'));
    }

    public function testFromEntityHandlesNullUpdatedAt(): void
    {
        $city = new City('Canberra');
        $stock = new Stock($city, 'Address', 0.0, 0.0);
        $this->setProperties($stock, [
            'id' => 'uuid-stock-789',
            'cityId' => 'uuid-city-101',
            'createdAt' => new DateTimeImmutable('2025-11-11 11:11:11'),
            'updatedAt' => null,
        ]);

        $dto = StockDtoMapper::fromEntity($stock);

        $this->assertNull($dto->updatedAt);
    }

    private function setProperties(object $object, array $data): void
    {
        $reflection = new ReflectionClass($object);
        foreach ($data as $property => $value) {
            if ($reflection->hasProperty($property)) {
                $prop = $reflection->getProperty($property);
                $prop->setValue($object, $value);
            }
        }
    }
}
