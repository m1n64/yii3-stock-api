<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\City;

use App\Application\Command\City\CreateCity\CreateCityCommand;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\City\CreateDocHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class CreateHandler extends AbstractCommandHandler implements CreateDocHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatchWithMapper(
            CreateCityCommand::class,
            $request->getParsedBody(),
        );
    }
}
