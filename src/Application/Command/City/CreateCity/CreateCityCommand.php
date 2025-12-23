<?php
declare(strict_types=1);

namespace App\Application\Command\City\CreateCity;

use Yiisoft\Validator\Rule\Required;

final readonly class CreateCityCommand
{
    /**
     * @param string $name
     */
    public function __construct(
        #[Required]
        public string $name,
    )
    {
    }
}
