<?php
declare(strict_types=1);

namespace App\Application\Command\City\DeleteCity;

use App\Application\Service\CityService;
use App\Shared\Application\Command\CommandHandlerInterface;
use Yiisoft\Yii\Cycle\Exception\NotFoundException;

/**
 * @implements CommandHandlerInterface<DeleteCityCommand, string>
 */
final class DeleteCityCommandHandler implements CommandHandlerInterface
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
     * @param DeleteCityCommand $command
     * @return string
     * @throws \Throwable
     * @throws NotFoundException
     */
    public function handle(object $command): string
    {
        $this->cityService->delete($command->id);
        return $command->id;
    }
}
