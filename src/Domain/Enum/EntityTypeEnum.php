<?php
declare(strict_types=1);

namespace App\Domain\Enum;

enum EntityTypeEnum: string
{
    case City = 'city';
    case Stock = 'stock';
}
