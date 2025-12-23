<?php
declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Contract\TransactionalRepositoryInterface;
use App\Domain\Entity\Stock;
use App\Domain\Model\NearbyStockResult;

interface StockRepositoryInterface extends TransactionalRepositoryInterface
{
    /**
     * @param int $limit
     * @param int $offset
     * @return Stock[]
     */
    public function findAll(int $limit, int $offset): array;

    /**
     * @param string $id
     * @return Stock|null
     */
    public function getById(string $id): Stock|null;

    /**
     * @param string $cityId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getByCityId(string $cityId, int $limit, int $offset): array;

    /**
     * @param Stock $stock
     * @return void
     */
    public function save(Stock $stock): void;

    /**
     * @param Stock $stock
     * @return void
     */
    public function delete(Stock $stock): void;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @param string $cityId
     * @return int
     */
    public function countByCity(string $cityId): int;

    /**
     * @param float $lan
     * @param float $lng
     * @return NearbyStockResult|null
     */
    public function findNearest(float $lat, float $lng): NearbyStockResult|null;
}
