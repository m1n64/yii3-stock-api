<?php
declare(strict_types=1);

namespace App\Domain\Model;

use App\Domain\Entity\Stock;

class NearbyStockResult
{
    /**
     * @var float
     */
    private float $distanceMeters;

    /**
     * @param Stock $stock
     * @param float $distanceMeters
     */
    public function __construct(
        private Stock $stock,
        float $distanceMeters,
    )
    {
        $this->distanceMeters = round($distanceMeters, 2);
    }

    /**
     * @return Stock
     */
    public function getStock(): Stock
    {
        return $this->stock;
    }

    /**
     * @return float
     */
    public function getDistanceMeters(): float
    {
        return $this->distanceMeters;
    }

    /**
     * @return float
     */
    public function getDistanceKm(): float
    {
        return round($this->distanceMeters / 1000, 2);
    }
}
