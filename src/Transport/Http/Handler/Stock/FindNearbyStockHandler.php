<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\Stock;

use App\Application\Query\Stock\FindNearbyStock\FindNearbyStockQuery;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\Stock\FindNearbyStockDocHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class FindNearbyStockHandler extends AbstractCommandHandler implements FindNearbyStockDocHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatchWithMapper(
            FindNearbyStockQuery::class,
            $request->getParsedBody(),
        );
    }
}
