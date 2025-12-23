<?php
declare(strict_types=1);

namespace App\Shared\Application\Pagination;

final readonly class PaginationValueObject
{
    /**
     * @var int
     */
    public int $page;

    /**
     * @var int
     */
    public int $limit;

    /**
     * @var int
     */
    public int $offset;

    /**
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $page, int $limit)
    {
        $this->page = max(1, $page);
        $this->limit = max(1, $limit);
        $this->offset = ($this->page - 1) * $this->limit;
    }
}
