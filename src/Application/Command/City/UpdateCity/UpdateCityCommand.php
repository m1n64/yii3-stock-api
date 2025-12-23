<?php
declare(strict_types=1);

namespace App\Application\Command\City\UpdateCity;

use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Uuid;

final class UpdateCityCommand
{
    /**
     * @param string $id
     * @param string $name
     */
    public function __construct(
        #[Required]
        #[Uuid]
        public string $id,

        #[Required]
        public string $name,
    )
    {
    }
}
