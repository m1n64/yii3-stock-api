<?php
declare(strict_types=1);

namespace App\Application\Service\Concern;

use App\Domain\Contract\TransactionalRepositoryInterface;
use Psr\Log\LoggerInterface;

trait HasTransactionalTrait
{
    /**
     * @return LoggerInterface
     */
    abstract protected function getLogger(): LoggerInterface;

    /**
     * @return TransactionalRepositoryInterface
     */
    abstract protected function getRepository(): TransactionalRepositoryInterface;

    /**
     * @param string $action
     * @param callable $fn
     * @return mixed
     * @throws \Throwable
     */
    protected function transactional(string $action, callable $fn): mixed
    {
        try {
            $this->getLogger()->info($action);

            return $this->getRepository()->transaction(function () use ($fn, $action) {
                $result = $fn();
                $this->getLogger()->info($action . ' success');
                return $result;
            });
        } catch (\Throwable $e) {
            $this->getLogger()->error($action . ' failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
