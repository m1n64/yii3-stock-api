<?php
declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\City;
use App\Domain\Repository\CityRepositoryInterface;
use Cycle\Database\DatabaseInterface;
use Cycle\ORM\EntityManager;
use Cycle\ORM\ORMInterface;
use Cycle\ORM\Select;

class CycleCityRepository implements CityRepositoryInterface
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
     * @param string $id
     * @return City|null
     */
    public function findById(string $id): City|null
    {
        return $this->select()
            ->where(['city_id' => $id])
            ->fetchOne();
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return array|City[]
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
     * @param City $city
     * @return void
     * @throws \Throwable
     */
    public function save(City $city): void
    {
        new EntityManager($this->orm)->persist($city)->run();
    }

    /**
     * @param City $city
     * @return void
     * @throws \Throwable
     */
    public function delete(City $city): void
    {
        $city->delete();
        $this->save($city);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->select()
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
        return $this->orm->getRepository(City::class)->select();
    }
}
