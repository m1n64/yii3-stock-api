<?php
declare(strict_types=1);

namespace App\Infrastructure;

use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use Psr\Container\ContainerInterface;

final class CommandBus implements CommandBusInterface
{
    /**
     * @param ContainerInterface $container
     */
    public function __construct(
        private ContainerInterface $container,
    )
    {
    }

    /**
     * @param object $command
     * @return mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function dispatch(object $command): mixed
    {
        $handlerClass = get_class($command) . 'Handler';
        if (!$this->container->has($handlerClass)) {
            throw new \RuntimeException("Handler class {$handlerClass} does not exist.");
        }

        $handler = $this->container->get($handlerClass);
        if (!$handler instanceof CommandHandlerInterface) {
            throw new \RuntimeException("Handler class {$handlerClass} must implement CommandHandlerInterface.");
        }

        return $handler->handle($command);
    }
}
