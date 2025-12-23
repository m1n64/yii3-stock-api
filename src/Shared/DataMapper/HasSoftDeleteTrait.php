<?php
declare(strict_types=1);

namespace App\Shared\DataMapper;

use Cycle\Annotated\Annotation\Column;
use DateTimeImmutable;

trait HasSoftDeleteTrait
{
    #[Column(type: 'datetime', name:'deleted_at', nullable: true)]
    private DateTimeImmutable|null $deletedAt = null {
        get {
            return $this->deletedAt;
        }
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $this->deletedAt = new DateTimeImmutable();
    }
}
