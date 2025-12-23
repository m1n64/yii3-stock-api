<?php
declare(strict_types=1);

namespace App\Application\Command\City\CreateCity;

use App\Application\Service\CityService;
use App\Domain\Entity\City;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<CreateCityCommand, City>
 */
final class CreateCityCommandHandler implements CommandHandlerInterface
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
     * @param CreateCityCommand $command
     * @return City
     * @throws \Throwable
     */
    public function handle(object $command): City
    {
        return $this->cityService->create($command->name);
    }
}
