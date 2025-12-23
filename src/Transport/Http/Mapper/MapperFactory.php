<?php
declare(strict_types=1);

namespace App\Transport\Http\Mapper;

final class MapperFactory
{
    /**
     * @param mixed $item
     * @return mixed
     */
    public static function mapItem(mixed $item): mixed
    {
        if (is_object($item)) {
            $class = $item::class;

            if (str_contains($class, 'Proxy')) {
                $class = get_parent_class($item) ?: $class;
            }

            $mapper = MapperRegistry::MAP[$class] ?? null;

            if ($mapper) {
                return $mapper::fromEntity($item);
            }
        }

        return $item;
    }
}
