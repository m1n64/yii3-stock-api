<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\City;

use App\Application\Query\City\GetCity\GetCityQuery;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\City\GetByIdDocHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Http\Status;

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
            GetCityQuery::class,
            ['id' => $id],
        );
    }
}
