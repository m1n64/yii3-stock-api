<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\ListStock;

use Yiisoft\Validator\Rule\Integer;

final class GetStocksQuery
{
    /**
     * @param int $page
     * @param int $limit
     */
    public function __construct(
        #[Integer(min: 1, skipOnEmpty: true)]
        public int $page = 1,

        #[Integer(min: 1, max: 100, skipOnEmpty: true)]
        public int $limit = 10,
    )
    {
    }
}
