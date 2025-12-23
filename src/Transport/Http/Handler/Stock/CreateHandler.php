<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\Stock;

use App\Application\Command\Stock\CreateStock\CreateStockCommand;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\Stock\CreateDocHandlerInterface;
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
            CreateStockCommand::class,
            $request->getParsedBody(),
        );
    }
}
