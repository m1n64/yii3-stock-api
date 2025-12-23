<?php
declare(strict_types=1);

namespace App\Shared\Application\Pagination;

final readonly class PaginatedResult
{
    /**
     * @param array $items
     * @param int $total
     * @param int $page
     * @param int $limit
     */
    public function __construct(
        public array $items,
        public int $total,
        public int $page,
        public int $limit,
    )
    {
    }

    /**
     * @return int
     */
    public function pages(): int
    {
        return (int) ceil($this->total / $this->limit);
    }

    /**
     * @param callable $callback
     * @return self
     */
    public function populateItems(callable $callback): self
    {
        $populatedItems = array_map($callback, $this->items);

        return new self(
            items: $populatedItems,
            total: $this->total,
            page: $this->page,
            limit: $this->limit,
        );
    }
}
