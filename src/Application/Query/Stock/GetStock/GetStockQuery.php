<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\GetStock;

use _PHPStan_5adafcbb8\Symfony\Contracts\Service\Attribute\Required;
use Yiisoft\Validator\Rule\Uuid;

final class GetStockQuery
{
    /**
     * @param string $id
     */
    public function __construct(
        #[Required]
        #[Uuid]
        public string $id,
    )
    {
    }
}
