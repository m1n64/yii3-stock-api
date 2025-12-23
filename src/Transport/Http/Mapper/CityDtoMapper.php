<?php
declare(strict_types=1);

namespace App\Transport\Http\Mapper;

use App\Domain\Entity\City;
use App\Transport\Http\Dto\CityDto;

final class CityDtoMapper
{
    /**
     * @param City $city
     * @return CityDto
     */
    public static function fromEntity(City $city): CityDto
    {
        return new CityDto(
            id: $city->getId(),
            name: $city->getName(),
            createdAt: $city->getCreatedAt(),
            updatedAt: $city->getUpdatedAt(),
        );
    }
}
