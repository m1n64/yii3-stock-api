<?php
declare(strict_types=1);

namespace App\Application\Service\Cache\Key;

final class StockKeyGenerator
{
    /**
     * @param string $stockId
     * @return string
     */
    public function generateForGet(string $stockId): string
    {
        return "find-by-id-cache-stock-$stockId";
    }
}
