<?php
declare(strict_types=1);

namespace App\Tests\Infrastructure\Event;

use App\Application\Listener\City\InvalidateCityCacheListener;
use App\Application\Listener\Stock\InvalidateNearbyCacheListener;
use App\Application\Listener\Stock\InvalidateStockCacheListener;
use App\Domain\Event\City\CityChanged;
use App\Domain\Event\Stock\StockChanged;
use App\Infrastructure\Event\EventProviderRegistry;
use PHPUnit\Framework\TestCase;

class EventProviderRegistryTest extends TestCase
{
    public function testEventsRegistryStructure(): void
    {
        $events = EventProviderRegistry::EVENTS;

        self::assertIsArray($events);
        self::assertNotEmpty($events);
    }

    public function testCityChangedListeners(): void
    {
        $events = EventProviderRegistry::EVENTS;

        self::assertArrayHasKey(CityChanged::class, $events);
        self::assertSame(
            [InvalidateCityCacheListener::class],
            $events[CityChanged::class]
        );
    }

    public function testStockChangedListeners(): void
    {
        $events = EventProviderRegistry::EVENTS;

        self::assertArrayHasKey(StockChanged::class, $events);
        self::assertSame(
            [
                InvalidateStockCacheListener::class,
                InvalidateNearbyCacheListener::class,
            ],
            $events[StockChanged::class]
        );
    }

    public function testAllListenersAreClasses(): void
    {
        foreach (EventProviderRegistry::EVENTS as $event => $listeners) {
            self::assertTrue(class_exists($event), "Event class {$event} does not exist");

            foreach ($listeners as $listener) {
                self::assertTrue(
                    class_exists($listener),
                    "Listener class {$listener} does not exist"
                );
            }
        }
    }
}
