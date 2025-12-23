<?php
declare(strict_types=1);

namespace App\Infrastructure\Pagination;

use App\Shared\Application\Pagination\PaginatedResult;
use Yiisoft\Data\Paginator\OffsetPaginator;

final class OffsetPaginatorAdapter
{
    /**
     * @param PaginatedResult $result
     * @return OffsetPaginator
     */
    public static function fromResult(PaginatedResult $result): OffsetPaginator
    {
        $reader = new StaticTotalDataReader(
            data: $result->items,
            totalCount: $result->total,
        );

        return new OffsetPaginator($reader)
            ->withPageSize($result->limit)
            ->withCurrentPage($result->page);
    }
}
