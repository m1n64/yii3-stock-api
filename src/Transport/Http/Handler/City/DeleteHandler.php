<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\City;

use App\Application\Command\City\DeleteCity\DeleteCityCommand;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\City\UpdateDocHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DeleteHandler extends AbstractCommandHandler implements UpdateDocHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $id = $this->route->getArgument('id');

        return $this->dispatch(
            DeleteCityCommand::class,
            ['id' => $id],
            fn() => $this->response->success(['city_id' => $id]),
        );
    }
}
