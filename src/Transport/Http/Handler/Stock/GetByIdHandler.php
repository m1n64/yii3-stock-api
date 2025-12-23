<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\Stock;

use App\Application\Query\Stock\GetStock\GetStockQuery;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\Stock\GetByIdDocHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class GetByIdHandler extends AbstractCommandHandler implements GetByIdDocHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = $this->route->getArgument('id');

        return $this->dispatchWithMapper(
            GetStockQuery::class,
            ['id' => $id],
        );
    }
}
