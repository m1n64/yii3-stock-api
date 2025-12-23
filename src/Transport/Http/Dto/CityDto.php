<?php
declare(strict_types=1);

namespace App\Transport\Http\Dto;

use DateTimeImmutable;
use OpenApi\Attributes as OA;

readonly class CityDto implements \JsonSerializable
{
    /**
     * @param string $id
     * @param string $name
     * @param DateTimeImmutable $createdAt
     * @param DateTimeImmutable|null $updatedAt
     */
    public function __construct(
        public string $id,
        public string $name,
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
            'name' => $this->name,
            'created_at' => $this->createdAt->format(DATE_ATOM),
            'updated_at' => $this->updatedAt?->format(DATE_ATOM),
        ];
    }
}
