<?php
declare(strict_types=1);

namespace App\Infrastructure\Pagination;

use Yiisoft\Data\Reader\CountableDataInterface;
use Yiisoft\Data\Reader\OffsetableDataInterface;
use Yiisoft\Data\Reader\ReadableDataInterface;

readonly class StaticTotalDataReader implements ReadableDataInterface, CountableDataInterface, OffsetableDataInterface
{
    /**
     * @param iterable $data
     * @param int $totalCount
     */
    public function __construct(
        private iterable $data,
        private int $totalCount,
    )
    {
    }

    /**
     * @param int $limit
     * @return $this
     */
    public function withLimit(int $limit): static
    {
        return $this;
    }

    /**
     * @return iterable
     */
    public function read(): iterable
    {
        return $this->data;
    }

    /**
     * @return array|object|null
     * @throws \Exception
     */
    public function readOne(): array|object|null
    {
        if (is_array($this->data)) {
            return $this->data === [] ? null : reset($this->data);
        }

        if ($this->data instanceof \IteratorAggregate) {
            $iterator = $this->data->getIterator();
        } elseif ($this->data instanceof \Iterator) {
            $iterator = $this->data;
        } else {
            $iterator = new \ArrayIterator(iterator_to_array($this->data, false));
        }

        $iterator->rewind();

        if (!$iterator->valid()) {
            return null;
        }

        return $iterator->current();
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->totalCount;
    }

    /**
     * @param int $offset
     * @return $this
     */
    public function withOffset(int $offset): static
    {
        return $this;
    }
}
