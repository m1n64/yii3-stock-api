<?php
declare(strict_types=1);

namespace App\Application\Query\City\ListCity;

use App\Application\Service\CityService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * @implements CommandHandlerInterface<GetCitiesQuery, PaginatedResult>
 */
final readonly class GetCitiesQueryHandler implements CommandHandlerInterface
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
     * @param GetCitiesQuery $command
     * @return PaginatedResult
     */
    public function handle(object $command): PaginatedResult
    {
        return $this->cityService->getAllCities($command->page, $command->limit);
    }
}
