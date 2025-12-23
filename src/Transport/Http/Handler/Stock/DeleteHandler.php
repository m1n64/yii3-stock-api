<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\Stock;

use App\Application\Command\Stock\DeleteStock\DeleteStockCommand;
use App\Shared\Http\Handler\AbstractCommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

final class DeleteHandler extends AbstractCommandHandler
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \ReflectionException
     */
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->dispatch(
            DeleteStockCommand::class,
            ['id' => $this->route->getArgument('id')],
            fn(string $id) => $this->response->success(['id' => $id]),
        );
    }
}
