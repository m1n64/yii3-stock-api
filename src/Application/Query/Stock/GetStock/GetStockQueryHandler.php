<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\GetStock;

use App\Application\Query\Stock\ListStock\GetStocksQuery;
use App\Application\Service\StockService;
use App\Domain\Entity\Stock;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<GetStockQuery, Stock|null>
 */
final class GetStockQueryHandler implements CommandHandlerInterface
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
     * @return Stock|null
     */
    public function handle(object $command): Stock|null
    {
        return $this->stockService->getStock($command->id);
    }
}
