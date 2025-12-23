<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\GetCityStockList;

use Yiisoft\Hydrator\Attribute\Parameter\Data;
use Yiisoft\Validator\Rule\Integer;
use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Uuid;

final class GetCityStockListQuery
{
    /**
     * @param string $cityId
     * @param int $page
     * @param int $limit
     */
    public function __construct(
        #[Data('city_id')]
        #[Required]
        #[Uuid]
        public string $cityId,

        #[Integer(min: 1, skipOnEmpty: true)]
        public int $page = 1,

        #[Integer(min: 1, max: 100, skipOnEmpty: true)]
        public int $limit = 20,
    )
    {
    }
}
