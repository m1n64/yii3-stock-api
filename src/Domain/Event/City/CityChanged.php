<?php
declare(strict_types=1);

namespace App\Domain\Event\City;

final readonly class CityChanged
{
    /**
     * @param string $cityId
     */
    public function __construct(
        public string $cityId,
    )
    {
    }
}
