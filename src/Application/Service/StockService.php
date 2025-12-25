<?php
declare(strict_types=1);

namespace App\Application\Service;

use App\Application\Service\Concern\HasTransactionalTrait;
use App\Domain\Contract\TransactionalRepositoryInterface;
use App\Domain\Entity\Stock;
use App\Domain\Event\Stock\StockChanged;
use App\Domain\Model\NearbyStockResult;
use App\Domain\Repository\CityRepositoryInterface;
use App\Domain\Repository\StockRepositoryInterface;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Shared\Application\Pagination\PaginationValueObject;
use InvalidArgumentException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\Log\LoggerInterface;


readonly class StockService
{
    use HasTransactionalTrait;

    /**
     * @param StockRepositoryInterface $repository
     * @param CityRepositoryInterface $cityRepository
     * @param LoggerInterface $logger
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(
        private StockRepositoryInterface $repository,
        private CityRepositoryInterface $cityRepository,
        private LoggerInterface $logger,
        private EventDispatcherInterface $eventDispatcher,
    )
    {
    }

    /**
     * @param int $page
     * @param int $limit
     * @return PaginatedResult
     */
    public function getAllStocks(int $page, int $limit): PaginatedResult
    {
        $this->logger->info("Fetching stocks: page {$page}, limit {$limit}");

        $pagination = new PaginationValueObject($page, $limit);

        $items = $this->repository->findAll($pagination->limit, $pagination->offset);
        $total = $this->repository->count();

        $this->logger->info("Fetched " . count($items) . " stocks");

        return new PaginatedResult(
            items: $items,
            total: $total,
            page: $page,
            limit: $limit,
        );
    }

    /**
     * @param string $id
     * @return Stock|null
     */
    public function getStock(string $id): Stock|null
    {
        $this->logger->info(sprintf('Fetching stock with ID: %s', $id));

        $stock = $this->repository->getById($id);

        if (!$stock) {
            $this->logger->warning("Stock not found with ID: $id");
        }

        return $stock;
    }

    /**
     * @param string $cityId
     * @param int $page
     * @param int $limit
     * @return PaginatedResult
     */
    public function getStocksByCityId(string $cityId, int $page, int $limit): PaginatedResult
    {
        $this->logger->info("Fetching stocks for city ID {$cityId}: page {$page}, limit {$limit}");

        $pagination = new PaginationValueObject($page, $limit);

        $items = $this->repository->getByCityId($cityId, $pagination->limit, $pagination->offset);
        $total = $this->repository->countByCity($cityId);

        $this->logger->info("Fetched " . count($items) . " stocks for city ID {$cityId}");

        return new PaginatedResult(
            items: $items,
            total: $total,
            page: $page,
            limit: $limit,
        );
    }

    /**
     * @param string $cityId
     * @param string $address
     * @param float $lat
     * @param float $lng
     * @return Stock
     * @throws \Throwable
     */
    public function create(string $cityId, string $address, float $lat, float $lng): Stock
    {
        return $this->transactional('Create stock', function () use ($cityId, $address, $lat, $lng): Stock {
            $city = $this->cityRepository->getById($cityId);
            if (!$city) {
                $this->logger->error("City not found with ID: $cityId");
                throw new \InvalidArgumentException("City not found with ID: $cityId");
            }

            $stock = new Stock(
                city: $city,
                address: $address,
                lat: $lat,
                lng: $lng,
            );

            $this->repository->save($stock);

            return $stock;
        });
    }

    /**
     * @param string $id
     * @param string|null $cityId
     * @param string|null $address
     * @param float|null $lat
     * @param float|null $lng
     * @return Stock
     * @throws \Throwable
     */
    public function update(string $id, string|null $cityId, string|null $address, float|null $lat, float|null $lng): Stock
    {
        return $this->transactional('Update stock', function () use ($id, $cityId, $address, $lat, $lng): Stock {
            $stock = $this->getStock($id);
            if (!$stock) {
                $this->logger->error("Stock with ID {$cityId} not found for update");
                throw new \InvalidArgumentException("Stock not found with ID: $cityId");
            }

            if ($cityId !== null) {
                $city = $this->cityRepository->getById($cityId);
                if (!$city) {
                    $this->logger->error("City not found with ID: $cityId");
                    throw new \InvalidArgumentException("City not found with ID: $cityId");
                }
                $stock->setCity($city);
            }

            if ($address !== null) {
                $stock->setAddress($address);
            }

            if ($lat !== null || $lng !== null) {
                if ($lat === null || $lng === null) {
                    throw new \InvalidArgumentException("Both coordinates are required for an update.");
                }

                $stock->setCoords($lat, $lng);
            }

            $this->repository->save($stock);

            $this->dispatchStockChanged($stock->getId(), $stock->getLat(), $stock->getLng());

            return $stock;
        });
    }

    /**
     * @param string $id
     * @return void
     * @throws \Throwable
     */
    public function delete(string $id): void
    {
        $this->transactional('Delete stock', function () use ($id): void {
            $stock = $this->getStock($id);
            if (!$stock) {
                $this->logger->error("Stock with ID {$id} not found for deletion");
                throw new \InvalidArgumentException("Stock not found with ID: $id");
            }

            $this->repository->delete($stock);

            $this->dispatchStockChanged($stock->getId(), $stock->getLat(), $stock->getLng());
        });
    }

    /**
     * @param float $lat
     * @param float $lng
     * @return NearbyStockResult|null
     * @throws InvalidArgumentException
     */
    public function findNearestStock(float $lat, float $lng): NearbyStockResult|null
    {
        $this->logger->info(sprintf('Finding nearest stock to coordinates: (%f, %f)', $lat, $lng));

        $nearestStock = $this->repository->findNearest($lat, $lng);

        if ($nearestStock) {
            $this->logger->info(sprintf(
                'Nearest stock found: ID %s at distance %f',
                $nearestStock->getStock()->getId(),
                $nearestStock->getDistanceMeters()
            ));
        } else {
            $this->logger->info('No nearest stock found');
        }

        return $nearestStock;
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

    /**
     * @param string $stockId
     * @param float $lat
     * @param float $lng
     * @return void
     */
    private function dispatchStockChanged(string $stockId, float $lat, float $lng): void
    {
        $this->eventDispatcher->dispatch(new StockChanged($stockId, $lat, $lng));
    }
}
