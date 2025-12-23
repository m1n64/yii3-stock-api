<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\GetCityStockList;

use App\Application\Service\StockService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * @implements CommandHandlerInterface<GetCityStockListQuery, PaginatedResult>
 */
final class GetCityStockListQueryHandler implements CommandHandlerInterface
{
    /**
     * @param StockService $stockService
     */
    public function __construct(
        private StockService $stockService,
    )
    {
    }

    /**
     * @param GetCityStockListQuery $command
     * @return PaginatedResult
     */
    public function handle(object $command): PaginatedResult
    {
        return $this->stockService->getStocksByCityId($command->cityId, $command->page, $command->limit);
    }
}
