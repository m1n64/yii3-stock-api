<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\Stock;

use App\Application\Command\Stock\UpdateStock\UpdateStockCommand;
use App\Domain\Entity\Stock;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\Stock\UpdateDocHandlerInterface;
use App\Transport\Http\Dto\StockDto;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Yiisoft\Arrays\ArrayHelper;

final class UpdateHandler extends AbstractCommandHandler implements UpdateDocHandlerInterface
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
            UpdateStockCommand::class,
            ArrayHelper::merge($request->getParsedBody(), ['id' => $id]),
            function (StockDto $stock) {
                try {
                    return $this->response->success($stock);
                } catch (\InvalidArgumentException $e) {
                    return $this->response->notFound();
                }
            }
        );
    }
}
