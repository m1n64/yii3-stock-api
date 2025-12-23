<?php
declare(strict_types=1);

namespace App\Shared\DataMapper;

use Cycle\ORM\Select\QueryBuilder;
use Cycle\ORM\Select\ScopeInterface;

final class SoftDeleteScope implements ScopeInterface
{
    /**
     * @param QueryBuilder $query
     * @return void
     */
    public function apply(QueryBuilder $query): void
    {
        $query->where(['deleted_at' => null]);
    }
}
