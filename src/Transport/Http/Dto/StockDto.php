<?php
declare(strict_types=1);

namespace App\Transport\Http\Dto;

use DateTimeImmutable;

readonly class StockDto implements \JsonSerializable
{
    /**
     * @param string $id
     * @param string $cityId
     * @param string $address
     * @param float $lat
     * @param float $lng
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        public string $id,
        public string $cityId,
        public string $address,
        public float $lat,
        public float $lng,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable|null $updatedAt = null,
    )
    {
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'city_id' => $this->cityId,
            'address' => $this->address,
            'lat' => $this->lat,
            'lng' => $this->lng,
            'created_at' => $this->createdAt->format(DATE_ATOM),
            'updated_at' => $this->updatedAt?->format(DATE_ATOM),
        ];
    }
}
