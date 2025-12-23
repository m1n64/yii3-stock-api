<?php
declare(strict_types=1);

namespace App\Domain\Entity;


use App\Shared\DataMapper\HasSoftDeleteTrait;
use App\Shared\DataMapper\SoftDeleteScope;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use DateTimeImmutable;

#[Entity(table: 'cities', scope: SoftDeleteScope::class)]
class City
{
    use HasSoftDeleteTrait;

    /**
     * @var string|null
     */
    #[Column(type: 'uuid', name: 'city_id', primary: true)]
    private string|null $id = null;

    /**
     * @var string
     */
    #[Column(type: 'string', size: 255)]
    private string $name;

    /**
     * @var DateTimeImmutable
     */
    #[Column(type: 'datetime', name: 'created_at')]
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    #[Column(type: 'datetime', name: 'updated_at', nullable: true)]
    private DateTimeImmutable|null $updatedAt = null;

    /**
     * @var array
     */
    #[HasMany(target: Stock::class, innerKey: 'city_id', outerKey: 'city_id')]
    private array $stocks = [];

    /**
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->createdAt = new DateTimeImmutable();
    }

    /**
     * @return string|null
     */
    public function getId(): string|null
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->name = $name;
        $this->touch();
    }

    /**
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): DateTimeImmutable|null
    {
        return $this->updatedAt;
    }

    /**
     * @return array
     */
    public function getStocks(): array
    {
        return $this->stocks;
    }

    /**
     * @return void
     */
    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
