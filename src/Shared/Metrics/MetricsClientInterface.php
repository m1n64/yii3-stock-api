<?php
declare(strict_types=1);

namespace App\Shared\Metrics;

use UnitEnum;

interface MetricsClientInterface
{
    /**
     * @param string|UnitEnum $measurement
     * @param float $ms
     * @param array $tags
     * @param int|null $timestampNs
     * @return void
     */
    public function timing(string|UnitEnum $measurement, float $ms, array $tags = [], int|null $timestampNs = null): void;

    /**
     * @param string|UnitEnum $measurement
     * @param int $value
     * @param array $tags
     * @param int|null $timestampNs
     * @return void
     */
    public function increment(string|UnitEnum $measurement, int $value = 1, array $tags = [], int|null $timestampNs = null): void;
}
