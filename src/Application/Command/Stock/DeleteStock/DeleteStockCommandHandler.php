<?php
declare(strict_types=1);

namespace App\Application\Command\Stock\DeleteStock;

use App\Application\Service\StockService;
use App\Shared\Application\Command\CommandHandlerInterface;

/**
 * @implements CommandHandlerInterface<DeleteStockCommand, string>
 */
final class DeleteStockCommandHandler implements CommandHandlerInterface
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
     * @param DeleteStockCommand $command
     * @return string
     * @throws \Throwable
     */
    public function handle(object $command): string
    {
        $this->stockService->delete($command->id);
        return $command->id;
    }
}
