<?php
declare(strict_types=1);

namespace App\Shared\Application\Command;

/**
 * @template TCommand of object
 * @template TResult
 */
interface CommandHandlerInterface
{
    /**
     * @param TCommand $command
     * @return TResult
     */
    public function handle(object $command): mixed;
}
