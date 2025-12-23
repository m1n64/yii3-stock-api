<?php
declare(strict_types=1);

namespace App\Application\Query\City\GetCity;

use App\Application\Service\CityService;
use App\Domain\Entity\City;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<GetCityQuery, City|null>
 */
final class GetCityQueryHandler implements CommandHandlerInterface
{
    /**
     * @param CityService $cityService
     */
    public function __construct(
        private CityService $cityService,
    )
    {
    }

    /**
     * @param GetCityQuery $command
     * @return City|null
     */
    public function handle(object $command): City|null
    {
        return $this->cityService->getCity($command->id);
    }
}
