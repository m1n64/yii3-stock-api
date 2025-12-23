<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\City;
use App\Domain\Entity\Stock;
use App\Domain\Model\NearbyStockResult;
use App\Domain\Repository\StockRepositoryInterface;
use Cycle\Database\DatabaseInterface;
use Cycle\Database\Injection\Expression;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;

class CycleStockRepository implements StockRepositoryInterface
{

    /**
     * @param ORMInterface $orm
     * @param DatabaseInterface $database
     */
    public function __construct(
        private ORMInterface $orm,
        private DatabaseInterface $database,
    )
    {
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Stock[]
     */
    public function findAll(int $limit, int $offset): array
    {
        return $this->select()
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->fetchAll();
    }

    /**
     * @param string $id
     * @return Stock|null
     */
    public function getById(string $id): Stock|null
    {
        return $this->select()
            ->where(['stock_id' => $id])
            ->fetchOne();
    }

    /**
     * @param string $cityId
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function getByCityId(string $cityId, int $limit, int $offset): array
    {
        return $this->select()
            ->where(['city_id' => $cityId])
            ->limit($limit)
            ->offset($offset)
            ->orderBy('created_at', 'DESC')
            ->fetchAll();
    }

    /**
     * @param Stock $stock
     * @return void
     * @throws \Throwable
     */
    public function save(Stock $stock): void
    {
        new EntityManager($this->orm)->persist($stock)->run();
    }

    /**
     * @param Stock $stock
     * @return void
     * @throws \Throwable
     */
    public function delete(Stock $stock): void
    {
        $stock->delete();
        $this->save($stock);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->select()->count();
    }

    /**
     * @param string $cityId
     * @return int
     */
    public function countByCity(string $cityId): int
    {
        return $this->select()
            ->where(['city_id' => $cityId])
            ->count();
    }

    /**
     * @template T
     * @param callable(): T $callback
     * @return T
     * @throws \Throwable
     */
    public function transaction(callable $callback): mixed
    {
        return $this->database->transaction($callback);
    }

    /**
     * @return Select
     */
    private function select(): Select
    {
        return $this->orm->getRepository(Stock::class)->select();
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return NearbyStockResult|null
     */
    public function findNearest(float $lat, float $lng): NearbyStockResult|null
    {
        $sql = "
        SELECT
            stock_id,
            ST_Distance(
                ST_SetSRID(st_makepoint(lng, lat), 4326)::geography,
                ST_SetSRID(ST_MakePoint(:lng, :lat), 4326)::geography
            ) as distance_meters
        FROM stocks
        WHERE deleted_at IS NULL
        ORDER BY
            CAST(ST_SetSRID(ST_MakePoint(lng, lat), 4326) AS geography) <->
            CAST(ST_SetSRID(ST_MakePoint(:lng, :lat), 4326) AS geography)
        LIMIT 1
    ";

        $row = $this->database->query($sql, ['lat' => $lat, 'lng' => $lng])->fetch();

        if (!$row) {
            return null;
        }

        $stock = $this->getById($row['stock_id']);

        if (!$stock) {
            return null;
        }

        return new NearbyStockResult(
            stock: $stock,
            distanceMeters: (float)$row['distance_meters']
        );
    }
}
