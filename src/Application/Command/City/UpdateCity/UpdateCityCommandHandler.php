<?php
declare(strict_types=1);

namespace App\Application\Command\City\UpdateCity;

use App\Application\Service\CityService;
use App\Domain\Entity\City;
use App\Shared\Application\Command\CommandHandlerInterface;
use Yiisoft\Yii\Cycle\Exception\NotFoundException;

/**
 * @implements CommandHandlerInterface<UpdateCityCommand, City>
 */
final class UpdateCityCommandHandler implements CommandHandlerInterface
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
     * @param UpdateCityCommand $command
     * @return City
     * @throws \Throwable
     * @throws NotFoundException
     */
    public function handle(object $command): City
    {
        return $this->cityService->update($command->id, $command->name);
    }
}
