<?php
declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Service\Concern\HasTransactionalTrait;
use App\Domain\Contract\TransactionalRepositoryInterface;
use App\Domain\Entity\City;
use App\Domain\Repository\CityRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Shared\Application\Pagination\PaginationValueObject;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

readonly class CityService
{
    use HasTransactionalTrait;

    /**
     * @param CityRepositoryInterface $repository
     * @param LoggerInterface $logger
     */
    public function __construct(
        private CityRepositoryInterface $repository,
        private LoggerInterface $logger,
    )
    {
    }

    /**
     * @param int $page
     * @param int $limit
     * @return PaginatedResult
     */
    public function getAllCities(int $page, int $limit): PaginatedResult
    {
        $this->logger->info("Fetching cities: page {$page}, limit {$limit}");

        $pagination = new PaginationValueObject($page, $limit);

        $items = $this->repository->findAll($pagination->limit, $pagination->offset);
        $total = $this->repository->count();

        $this->logger->info("Fetched " . count($items) . " cities out of {$total} total");

        return new PaginatedResult(
            items: $items,
            total: $total,
            page: $page,
            limit: $limit,
        );
    }

    /**
     * @param string $id
     * @return City|null
     */
    public function getCity(string $id): City|null
    {
        $this->logger->info(sprintf('Fetching city with ID: %s', $id));

        $city = $this->repository->findById($id);

        if (!$city) {
            $this->logger->warning(sprintf('City with ID %s not found', $id));
        }

        return $city;
    }

    /**
     * @param string $name
     * @return City
     * @throws \Throwable
     */
    public function create(string $name): City
    {
        return $this->transactional('Create city', function () use ($name) {
            $city = new City($name);
            $this->repository->save($city);
            return $city;
        });
    }

    /**
     * @param string $id
     * @param string $name
     * @return City
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function update(string $id, string $name): City
    {
        return $this->transactional('Update city', function () use ($id, $name) {
            $city = $this->getCity($id);
            if (!$city) {
                $this->logger->warning("City with ID {$id} not found for update");
                throw new InvalidArgumentException(sprintf('City with ID %s not found', $id));
            }

            $city->setName($name);
            $this->repository->save($city);

            return $city;
        });
    }

    /**
     * @param string $id
     * @return void
     * @throws InvalidArgumentException
     * @throws \Throwable
     */
    public function delete(string $id): void
    {
        $this->transactional('Delete city', function () use ($id) {
            $city = $this->getCity($id);
            if (!$city) {
                $this->logger->warning("City with ID {$id} not found for deletion");
                throw new InvalidArgumentException(sprintf('City with ID %s not found', $id));
            }

            $this->repository->delete($city);
        });
    }

    /**
     * @return LoggerInterface
     */
    protected function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return TransactionalRepositoryInterface
     */
    protected function getRepository(): TransactionalRepositoryInterface
    {
        return $this->repository;
    }
}
