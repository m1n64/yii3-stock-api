<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\FindNearbyStock;

use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Type\FloatType;

final class FindNearbyStockQuery
{
    /**
     * @param float $lat
     * @param float $lng
     */
    public function __construct(
        #[Required]
        #[FloatType]
        public float $lat,

        #[Required]
        #[FloatType]
        public float $lng,
    )
    {
    }
}
