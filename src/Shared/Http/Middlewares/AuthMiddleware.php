<?php
declare(strict_types=1);

namespace App\Shared\Http\Middlewares;

use App\Shared\Http\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Yiisoft\Http\Header;

final class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @param ResponseFactory $responseFactory
     * @param string $secretKey
     */
    public function __construct(ResponseFactory $responseFactory, private string $secretKey)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $header = $request->getHeaderLine(Header::AUTHORIZATION);
        if ($header !== 'Bearer ' . $this->secretKey) {
            return $this->responseFactory->fail('Unauthorized', httpCode: 401);
        }

        return $handler->handle($request);
    }
}
