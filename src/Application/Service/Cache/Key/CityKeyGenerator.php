<?php
declare(strict_types=1);

namespace App\Application\Service\Cache\Key;

final class CityKeyGenerator
{
    /**
     * @param string $cityId
     * @return string
     */
    public function generateForGet(string $cityId): string
    {
        return "find-by-id-cache-stock-$cityId";
    }
}
