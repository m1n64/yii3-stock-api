<?php
declare(strict_types=1);

namespace App\Transport\Http\Handler\City;

use App\Application\Command\City\UpdateCity\UpdateCityCommand;
use App\Domain\Entity\City;
use App\Shared\Http\Handler\AbstractCommandHandler;
use App\Transport\Http\ApiDoc\City\UpdateDocHandlerInterface;
use App\Transport\Http\Dto\CityDto;
use InvalidArgumentException;
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
        return $this->dispatchWithMapper(
            UpdateCityCommand::class,
            ArrayHelper::merge($request->getParsedBody(), ['id' => $this->route->getArgument('id')]),
            function (CityDto $city) {
                try {
                    return $this->response->success($city);
                } catch (InvalidArgumentException $e) {
                    return $this->response->notFound();
                }
            }
        );
    }
}
