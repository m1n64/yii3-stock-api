<?php
declare(strict_types=1);

namespace App\Shared\Http\Handler;

use App\Infrastructure\Pagination\OffsetPaginatorAdapter;
use App\Shared\Application\Command\CommandBusInterface;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Shared\Http\Presenter\OffsetPaginatorPresenter;
use App\Shared\Http\ResponseFactory;
use App\Transport\Http\Mapper\MapperFactory;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Http\Status;
use Yiisoft\Hydrator\HydratorInterface;
use Yiisoft\Router\CurrentRoute;
use Yiisoft\Validator\ValidatorInterface;

abstract class AbstractCommandHandler implements HandlerInterface
{
    /**
     * @param HydratorInterface $hydrator
     * @param ValidatorInterface $validator
     * @param CommandBusInterface $bus
     * @param ResponseFactory $response
     * @param CurrentRoute $route
     * @param MapperFactory $mapperFactory
     */
    public function __construct(
        protected HydratorInterface $hydrator,
        protected ValidatorInterface $validator,
        protected CommandBusInterface $bus,
        protected ResponseFactory $response,
        protected CurrentRoute $route,
        private MapperFactory $mapperFactory,
    )
    {
    }

    /**
     * @param string $commandClass
     * @param array $data
     * @param callable|null $onSuccess
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    protected function dispatch(
        string $commandClass,
        array $data,
        callable|null $onSuccess = null,
    ): ResponseInterface
    {
        $command = $this->hydrator->create($commandClass, $data);

        $validation = $this->validator->validate($command);
        if (!$validation->isValid()) {
            return $this->response->failValidation($validation);
        }

        try {
            $result = $this->bus->dispatch($command);
            if (!$result) {
                return $this->response->notFound();
            }

            return $onSuccess
                ? $onSuccess($result)
                : $this->response->success($result);
        } catch (\Exception $exception) {
            return $this->response->fail($exception->getMessage(), code: $exception->getCode() ?? Status::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $commandClass
     * @param array $data
     * @param callable|null $onSuccess
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    protected function dispatchWithMapper(
        string $commandClass,
        array $data,
        callable|null $onSuccess = null,
    ): ResponseInterface
    {
        return $this->dispatch(
            commandClass: $commandClass,
            data: $data,
            onSuccess: function ($response) use ($onSuccess) {
                $mapped = $this->mapperFactory->mapItem($response);

                return $onSuccess
                    ? $onSuccess($mapped)
                    : $this->response->success($mapped);
            },
        );
    }

    /**
     * @param PaginatedResult $result
     * @return ResponseInterface
     */
    protected function paginateResponse(PaginatedResult $result): ResponseInterface
    {
        $paginator = OffsetPaginatorAdapter::fromResult($result);
        return $this->response->success($paginator, new OffsetPaginatorPresenter());
    }

    /**
     * @param PaginatedResult $result
     * @return ResponseInterface
     */
    protected function paginateResponseWithMapper(PaginatedResult $result): ResponseInterface
    {
        $result = $result->populateItems(fn(mixed $item) => $this->mapperFactory->mapItem($item));

        return $this->paginateResponse($result);
    }
}
