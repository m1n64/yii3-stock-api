<?php
declare(strict_types=1);

namespace App\Tests\Transport\Http\Mapper;

use App\Domain\Entity\City;
use App\Transport\Http\Dto\CityDto;
use App\Transport\Http\Mapper\MapperFactory;
use Codeception\Test\Unit;
use ReflectionClass;

class MapperFactoryTest extends Unit
{
    private MapperFactory $mapperFactory;

    protected function _before()
    {
        $this->mapperFactory = new MapperFactory();
    }

    public function testMapItemReturnsDtoForRegisteredEntity(): void
    {
        $city = new City('Los Santos');
        $this->setPrivateProperty($city, 'id', 'uuid-atlantis-123');

        $result = $this->mapperFactory->mapItem($city);

        $this->assertInstanceOf(CityDto::class, $result);
        $this->assertSame('Los Santos', $result->name);
        $this->assertSame('uuid-atlantis-123', $result->id);
    }

    public function testMapItemHandlesProxyClasses(): void
    {
        if (!class_exists('App\Domain\Entity\CityProxy')) {
            class_alias(City::class, 'App\Domain\Entity\CityProxy');
        }

        $proxyCity = new \App\Domain\Entity\CityProxy('Silent Hill');
        $this->setPrivateProperty($proxyCity, 'id', 'uuid-silent-hill-666');

        $result = $this->mapperFactory->mapItem($proxyCity);

        $this->assertInstanceOf(CityDto::class, $result);
        $this->assertSame('Silent Hill', $result->name);
    }

    public function testMapItemReturnsOriginalIfNoMapperFound(): void
    {
        $item = new \stdClass();
        $item->data = 'Night City';

        $result = $this->mapperFactory->mapItem($item);

        $this->assertSame($item, $result);
    }

    public function testMapItemHandlesScalars(): void
    {
        $this->assertSame('Raccoon City', $this->mapperFactory->mapItem('Raccoon City'));
        $this->assertSame(42, $this->mapperFactory->mapItem(42));
    }

    private function setPrivateProperty(object $object, string $propertyName, mixed $value): void
    {
        $reflection = new ReflectionClass($object);

        while ($reflection && !$reflection->hasProperty($propertyName)) {
            $reflection = $reflection->getParentClass();
        }

        if (!$reflection) {
            throw new \InvalidArgumentException("Property $propertyName not found");
        }

        $property = $reflection->getProperty($propertyName);
        $property->setValue($object, $value);
    }
}
