<?php
declare(strict_types=1);

namespace App\Infrastructure\Event;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\ListenerProviderInterface;

final class ConfigurableListenerProvider implements ListenerProviderInterface
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
     * @param object $event
     * @return iterable
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventClass = $event::class;

        foreach ((EventProviderRegistry::EVENTS[$eventClass] ?? []) as $listenerClass) {
            yield $this->container->get($listenerClass);
        }
    }
}
