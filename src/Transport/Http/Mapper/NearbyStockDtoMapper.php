<?php
declare(strict_types=1);

namespace App\Transport\Http\Mapper;

use App\Domain\Model\NearbyStockResult;
use App\Transport\Http\Dto\NearbyStockDto;

final class NearbyStockDtoMapper
{
    /**
     * @param NearbyStockResult $result
     * @return NearbyStockDto
     */
    public static function fromEntity(NearbyStockResult $result): NearbyStockDto
    {
        return new NearbyStockDto(
            city: CityDtoMapper::fromEntity($result->getStock()->getCity()),
            stock: StockDtoMapper::fromEntity($result->getStock()),
            distanceMeters: $result->getDistanceMeters(),
        );
    }
}
