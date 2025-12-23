<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\ListStock;

use App\Application\Service\StockService;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Pagination\PaginatedResult;

/**
 * @implements CommandHandlerInterface<GetStocksQuery, PaginatedResult>
 */
final class GetStocksQueryHandler implements CommandHandlerInterface
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
     * @param GetStocksQuery $command
     * @return PaginatedResult
     */
    public function handle(object $command): PaginatedResult
    {
        return $this->stockService->getAllStocks($command->page, $command->limit);
    }
}
