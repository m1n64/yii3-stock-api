<?php
declare(strict_types=1);

namespace App\Domain\Contract;

interface TransactionalRepositoryInterface
{
    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     */
    public function transaction(callable $callback): mixed;
}
