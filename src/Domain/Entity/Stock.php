<?php
declare(strict_types=1);

namespace App\Domain\Entity;

use App\Shared\DataMapper\HasSoftDeleteTrait;
use App\Shared\DataMapper\SoftDeleteScope;
use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;
use DateTimeImmutable;

#[Entity(table: 'stocks', scope: SoftDeleteScope::class)]
class Stock
{
    use HasSoftDeleteTrait;

    /**
     * @var string|null
     */
    #[Column(type: 'uuid', name: 'stock_id', primary: true)]
    private string|null $id = null;

    /**
     * @var string
     */
    #[Column(type: 'uuid', name: 'city_id')]
    private string $cityId;

    /**
     * @var string
     */
    #[Column(type: 'string', size: 255)]
    private string $address;

    /**
     * @var float
     */
    #[Column(type: 'double')]
    private float $lat;

    /**
     * @var float
     */
    #[Column(type: 'double')]
    private float $lng;

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
     * @var City
     */
    #[BelongsTo(target: City::class, innerKey: 'city_id', outerKey: 'city_id')]
    private City $city;

    /**
     * @param City $city
     * @param string $address
     * @param float $lat
     * @param float $lng
     */
    public function __construct(City $city, string $address, float $lat, float $lng)
    {
        $this->city = $city;
        $this->address = $address;
        $this->lat = $lat;
        $this->lng = $lng;
        $this->createdAt = new DateTimeImmutable();
    }


    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCityId(): string
    {
        return $this->cityId;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     * @return void
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
        $this->touch();
    }

    /**
     * @return float
     */
    public function getLat(): float
    {
        return $this->lat;
    }

    /**
     * @return float
     */
    public function getLng(): float
    {
        return $this->lng;
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return void
     */
    public function setCoords(float $lat, float $lng): void
    {
        $this->lat = $lat;
        $this->lng = $lng;
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
     * @return City
     */
    public function getCity(): City
    {
        return $this->city;
    }

    /**
     * @param City $city
     * @return void
     */
    public function setCity(City $city): void
    {
        $this->city = $city;
        $this->cityId = $city->getId() ?? $this->cityId;
        $this->touch();
    }

    /**
     * @return void
     */
    private function touch(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }
}
