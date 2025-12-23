<?php
declare(strict_types=1);

namespace App\Tests\Transport\Http\Mapper;

use App\Domain\Entity\City;
use App\Transport\Http\Dto\CityDto;
use App\Transport\Http\Mapper\CityDtoMapper;
use Codeception\Test\Unit;
use DateTimeImmutable;
use ReflectionClass;

class CityDtoMapperTest extends Unit
{
    public function testFromEntityCorrectlyMapsData(): void
    {
        $cityName = 'New Mexico';
        $cityId = '550e8400-e29b-41d4-a716-446655440000';
        $city = new City($cityName);

        $this->setPrivateProperty($city, 'id', $cityId);

        $dto = CityDtoMapper::fromEntity($city);

        $this->assertInstanceOf(CityDto::class, $dto);
        $this->assertSame($cityId, $dto->id);
        $this->assertSame($cityName, $dto->name);
        $this->assertInstanceOf(DateTimeImmutable::class, $dto->createdAt);
        $this->assertSame($city->getCreatedAt()->format('c'), $dto->createdAt->format('c'));
    }

    public function testMappingWithUpdatedAt(): void
    {
        $city = new City('Washington');
        $city->setName('Washington DC');
        $this->setPrivateProperty($city, 'id', '123e4567-e89b-12d3-a456-426614174000');

        $dto = CityDtoMapper::fromEntity($city);

        $this->assertNotNull($dto->updatedAt);
        $this->assertInstanceOf(DateTimeImmutable::class, $dto->updatedAt);
    }

    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($propertyName);
        $property->setValue($object, $value);
    }
}
