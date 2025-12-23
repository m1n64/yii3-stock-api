<?php
declare(strict_types=1);

namespace App\Application\Query\City\GetCity;

use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Uuid;

final class GetCityQuery
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
