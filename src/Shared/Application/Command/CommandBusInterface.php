<?php
declare(strict_types=1);

namespace App\Shared\Application\Command;

/**
 * @template T of object
 * @template R of mixed
 */
interface CommandBusInterface
{
    /**
     * @param T $command
     * @return R
     */
    public function dispatch(object $command): mixed;
}
