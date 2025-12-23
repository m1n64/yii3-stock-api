<?php
declare(strict_types=1);

namespace App\Application\Query\Stock\FindNearbyStock;

use App\Application\Service\StockService;
use App\Domain\Model\NearbyStockResult;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<FindNearbyStockQuery, NearbyStockResult|null>
 */
final class FindNearbyStockQueryHandler implements CommandHandlerInterface
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
     * @param FindNearbyStockQuery $command
     * @return NearbyStockResult|null
     */
    public function handle(object $command): NearbyStockResult|null
    {
        return $this->stockService->findNearestStock($command->lat, $command->lng);
    }
}
