<?php
declare(strict_types=1);

namespace App\Application\Command\Stock\CreateStock;

use App\Application\Service\StockService;
use App\Domain\Entity\Stock;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<CreateStockCommand, Stock>
 */
final class CreateStockCommandHandler implements CommandHandlerInterface
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
     * @param CreateStockCommand $command
     * @return Stock
     * @throws \Throwable
     */
    public function handle(object $command): Stock
    {
        return $this->stockService->create(
            $command->cityId,
            $command->address,
            $command->lat,
            $command->lng,
        );
    }
}
