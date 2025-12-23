<?php
declare(strict_types=1);

namespace App\Transport\Http\Dto;

readonly class NearbyStockDto implements \JsonSerializable
{
    /**
     * @param CityDto $city
     * @param StockDto $stock
     * @param float $distanceMeters
     */
    public function __construct(
        public CityDto $city,
        public StockDto $stock,
        public float $distanceMeters,
    )
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'stock' => [
                'id' => $this->stock->id,
                'city_id' => $this->stock->cityId,
                'address' => $this->stock->address,
                'lat' => $this->stock->lat,
                'lng' => $this->stock->lng,
                'createdAt' => $this->stock->createdAt->format(DATE_ATOM),
                'updatedAt' => $this->stock->updatedAt?->format(DATE_ATOM),
                'city' => [
                    'id' => $this->city->id,
                    'name' => $this->city->name,
                    'createdAt' => $this->city->createdAt->format(DATE_ATOM),
                    'updatedAt' => $this->city->updatedAt?->format(DATE_ATOM),
                ]
            ],
            'distance_meters' => $this->distanceMeters
        ];
    }
}
