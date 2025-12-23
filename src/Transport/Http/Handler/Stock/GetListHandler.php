<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\Stock;

use App\Application\Query\Stock\ListStock\GetStocksQuery;
use App\Infrastructure\Pagination\OffsetPaginatorAdapter;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Shared\Http\Presenter\OffsetPaginatorPresenter;
use App\Transport\Http\ApiDoc\Stock\GetListDocHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetListHandler extends AbstractCommandHandler implements GetListDocHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatch(
            GetStocksQuery::class,
            $request->getQueryParams(),
            fn(PaginatedResult $result) => $this->paginateResponseWithMapper($result),
        );
    }
}
