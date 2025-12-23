<?php
declare(strict_types=1);

namespace App\Application\Command\Stock\DeleteStock;

use Yiisoft\Validator\Rule\Required;
use Yiisoft\Validator\Rule\Uuid;

final class DeleteStockCommand
{
    /**
     * @param string $id
     */
    public function __construct(
        #[Required]
        #[Uuid]
        public string $id,
    )
    {
    }
}
