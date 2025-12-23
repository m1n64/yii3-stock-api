<?php
declare(strict_types=1);

namespace App\Shared\Http\Middlewares;

use App\Shared\Logger\TraceStorage;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class TraceMiddleware implements MiddlewareInterface
{
    /**
     *
     */
    public const HEADER_NAME = 'traceparent';

    /**
     * @param TraceStorage $traceStorage
     */
    public function __construct(
        private TraceStorage $traceStorage,
    )
    {
    }

    /**
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     * @throws \Random\RandomException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $traceParent = $request->getHeaderLine(self::HEADER_NAME);

        if (empty($traceParent)) {
            $traceParent = sprintf('00-%s-%s-01', bin2hex(random_bytes(16)), bin2hex(random_bytes(8)));
        }

        $this->traceStorage->set($traceParent);

        $response = $handler->handle($request);

        if ($response instanceof ResponseInterface) {
            return $response->withHeader(self::HEADER_NAME, $traceParent);
        }

        return $response;
    }
}
