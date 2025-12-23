<?php
declare(strict_types=1);

namespace App\Application\Command\Stock\UpdateStock;

use App\Application\Service\StockService;
use App\Domain\Entity\Stock;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<UpdateStockCommand, Stock>
 */
final class UpdateStockCommandHandler implements CommandHandlerInterface
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
     * @param UpdateStockCommand $command
     * @return Stock
     * @throws \Throwable
     */
    public function handle(object $command): Stock
    {
        return $this->stockService->update($command->id, $command->cityId, $command->address, $command->lat, $command->lng);
    }
}
