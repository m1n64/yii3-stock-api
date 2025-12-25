<?php
declare(strict_types=1);

namespace App\Domain\Event\Stock;

final readonly class StockChanged
{
    /**
     * @param string $stockId
     * @param float $lat
     * @param float $lng
     */
    public function __construct(
        public string $stockId,
        public float $lat,
        public float $lng,
    )
    {
    }
}
