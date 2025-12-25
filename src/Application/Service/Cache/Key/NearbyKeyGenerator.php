<?php
declare(strict_types=1);

namespace App\Application\Service\Cache\Key;

final class NearbyKeyGenerator
{
    /**
     * @param float $lat
     * @param float $lng
     * @return string
     */
    public function generate(float $lat, float $lng): string
    {
        $lat = round($lat, 4);
        $lng = round($lng, 4);

        $payload = sprintf('nearby:%0.4f:%0.4f', $lat, $lng);
        $hash = sprintf('%u', crc32($payload));

        return "find-nearby-cache-$hash";
    }
}
