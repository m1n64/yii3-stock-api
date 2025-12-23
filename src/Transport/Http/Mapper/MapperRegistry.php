<?php
declare(strict_types=1);

namespace App\Transport\Http\Mapper;

use App\Domain\Entity\City;
use App\Domain\Entity\Stock;
use App\Domain\Model\NearbyStockResult;
use App\Transport\Http\Dto\CityDto;

final class MapperRegistry
{
    /**
     *
     */
    public const MAP = [
        City::class => CityDtoMapper::class,
        Stock::class => StockDtoMapper::class,
        NearbyStockResult::class => NearbyStockDtoMapper::class,
    ];
}
