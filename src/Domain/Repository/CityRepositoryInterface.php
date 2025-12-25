<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Contract\TransactionalRepositoryInterface;
use App\Domain\Entity\City;

interface CityRepositoryInterface extends TransactionalRepositoryInterface
{
    /**
     * @param int $limit
     * @param int $offset
     * @return City[]
     */
    public function findAll(int $limit, int $offset): array;

    /**
     * @param string $id
     * @return City|null
     */
    public function getById(string $id): City|null;

    /**
     * @param City $city
     * @return void
     */
    public function save(City $city): void;

    /**
     * @param City $city
     * @return void
     */
    public function delete(City $city): void;

    /**
     * @return int
     */
    public function count(): int;
}
