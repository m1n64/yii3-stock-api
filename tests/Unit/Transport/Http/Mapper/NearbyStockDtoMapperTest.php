<?php
declare(strict_types=1);

namespace App\Tests\Transport\Http\Mapper;

use App\Domain\Entity\City;
use App\Domain\Entity\Stock;
use App\Domain\Model\NearbyStockResult;
use App\Transport\Http\Dto\NearbyStockDto;
use App\Transport\Http\Mapper\NearbyStockDtoMapper;
use Codeception\Test\Unit;
use ReflectionClass;

class NearbyStockDtoMapperTest extends Unit
{
    public function testFromEntityMapsDeepRelationsCorrectly(): void
    {
        $city = new City('Dublin');
        $cityId = 'uuid-city-123';
        $this->setProperties($city, ['id' => $cityId]);

        $stock = new Stock($city, 'St. Patric str', 43.2, 76.9);
        $stockId = 'uuid-stock-999';
        $this->setProperties($stock, [
            'id' => $stockId,
            'cityId' => $cityId,
        ]);

        $distance = 500.5;
        $resultModel = new NearbyStockResult($stock, $distance);

        $dto = NearbyStockDtoMapper::fromEntity($resultModel);

        $this->assertInstanceOf(NearbyStockDto::class, $dto);

        $this->assertEquals($distance, $dto->distanceMeters);

        $this->assertEquals($stockId, $dto->stock->id);
        $this->assertEquals($cityId, $dto->stock->cityId);
        $this->assertEquals('St. Patric str', $dto->stock->address);

        $this->assertEquals($cityId, $dto->city->id);
        $this->assertEquals('Dublin', $dto->city->name);
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
