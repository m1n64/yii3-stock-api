<?php
declare(strict_types=1);

namespace App\Application\Command\Stock\CreateStock;

use Yiisoft\Hydrator\Attribute\Parameter\Data;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Type\FloatType;
use Yiisoft\Validator\Rule\Uuid;

final class CreateStockCommand
{
    /**
     * @param string $cityId
     * @param string $address
     * @param float $lat
     * @param float $lng
     */
    public function __construct(
        #[Data('city_id')]
        #[Required]
        #[Uuid]
        public string $cityId,

        #[Required]
        public string $address,

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
