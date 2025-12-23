<?php
declare(strict_types=1);

namespace App\Transport\Http\Mapper;

use App\Domain\Entity\Stock;
use App\Transport\Http\Dto\StockDto;

final class StockDtoMapper
{
    /**
     * @param Stock $stock
     * @return StockDto
     */
    public static function fromEntity(Stock $stock): StockDto
    {
        return new StockDto(
            id: $stock->getId(),
            cityId: $stock->getCityId(),
            address: $stock->getAddress(),
            lat: $stock->getLat(),
            lng: $stock->getLng(),
            createdAt: $stock->getCreatedAt(),
            updatedAt: $stock->getUpdatedAt(),
        );
    }
}
