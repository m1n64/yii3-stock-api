<?php
declare(strict_types=1);

namespace App\Application\Command\City\DeleteCity;

final class DeleteCityCommand
{
    /**
     * @param string $id
     */
    public function __construct(
        public string $id,
    )
    {
    }
}
