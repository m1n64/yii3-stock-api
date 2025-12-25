<?php

declare(strict_types=1);

namespace App\Shared\Http;

use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Throwable;
use Yiisoft\ErrorHandler\Exception\UserException;
use Yiisoft\ErrorHandler\Middleware\ExceptionResponder;
use Yiisoft\Injector\Injector;
use Yiisoft\Input\Http\InputValidationException;

final readonly class ExceptionResponderFactory
{
    /**
     * @param ResponseFactoryInterface $psrResponseFactory
     * @param ResponseFactory $apiResponseFactory
     * @param Injector $injector
     */
    public function __construct(
        private ResponseFactoryInterface $psrResponseFactory,
        private ResponseFactory $apiResponseFactory,
        private Injector $injector,
    )
    {
    }

    /**
     * @return ExceptionResponder
     */
    public function create(): ExceptionResponder
    {
        return new ExceptionResponder(
            [
                InputValidationException::class => $this->inputValidationException(...),
                Throwable::class => $this->throwable(...),
            ],
            $this->psrResponseFactory,
            $this->injector,
        );
    }

    /**
     * @param InputValidationException $exception
     * @return ResponseInterface
     */
    private function inputValidationException(InputValidationException $exception): ResponseInterface
    {
        return $this->apiResponseFactory->failValidation($exception->getResult());
    }

    /**
     * @param Throwable $exception
     * @return ResponseInterface
     * @throws Throwable
     */
    private function throwable(Throwable $exception): ResponseInterface
    {
        if (UserException::isUserException($exception)) {
            $code = $exception->getCode();
            return $this->apiResponseFactory->fail(
                $exception->getMessage(),
                code: is_int($code) ? $code : null,
            );
        }
        throw $exception;
    }
}
