<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\City;

use App\Application\Query\Stock\GetCityStockList\GetCityStockListQuery;
use App\Shared\Application\Pagination\PaginatedResult;
use App\Shared\Http\Handler\AbstractCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Arrays\ArrayHelper;

final class GetCityStockListHandler extends AbstractCommandHandler
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $cityId = $this->route->getArgument('id');

        return $this->dispatch(
            GetCityStockListQuery::class,
            ArrayHelper::merge($request->getQueryParams(), ['city_id' => $cityId]),
            fn(PaginatedResult $result) => $this->paginateResponseWithMapper($result),
        );
    }
}
