<?php

declare(strict_types=1);

use App\Shared\Http\Middlewares\AuthMiddleware;
use App\Transport\Http\Handler\City\GetCityStockListHandler;
use App\Transport\Http\Handler\Stock\CreateHandler;
use App\Transport\Http\Handler\Stock\DeleteHandler;
use App\Transport\Http\Handler\Stock\FindNearbyStockHandler;
use App\Transport\Http\Handler\Stock\GetByIdHandler;
use App\Transport\Http\Handler\Stock\GetListHandler;
use App\Transport\Http\Handler\Stock\UpdateHandler;
use App\Transport\Http\Middleware\FindByIdCacheMiddleware;
use App\Transport\Http\Middleware\FindNearestCacheMiddleware;
use Psr\Http\Message\ResponseFactoryInterface;
use Yiisoft\Aliases\Aliases;
use Yiisoft\DataResponse\Middleware\FormatDataResponseAsJson;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;

/**
 * @var array $params
 */

return [
    Route::get('/docs')
        ->action(function (ResponseFactoryInterface $responseFactory, Aliases $aliases) {
            $path = $aliases->get('@public/docs.html');

            if (!file_exists($path)) {
                return $responseFactory->createResponse(404);
            }

            $content = file_get_contents($path);
            $response = $responseFactory->createResponse();
            $response->getBody()->write($content);

            return $response->withHeader('Content-Type', 'text/html');
        })
        ->name('docs'),

    Group::create('/api')
        ->middleware(FormatDataResponseAsJson::class)
        ->routes(
            Route::get('/cities')
                ->action(\App\Transport\Http\Handler\City\GetListHandler::class)
                ->name('city/list'),

            Route::get('/cities/{id}')
                ->middleware(FindByIdCacheMiddleware::class)
                ->action(\App\Transport\Http\Handler\City\GetByIdHandler::class)
                ->name('city/by-id'),

            Route::post('/cities')
                ->middleware(AuthMiddleware::class)
                ->action(\App\Transport\Http\Handler\City\CreateHandler::class)
                ->name('city/create'),

            Route::patch('/cities/{id}')
                ->middleware(AuthMiddleware::class)
                ->action(\App\Transport\Http\Handler\City\UpdateHandler::class)
                ->name('city/update'),

            Route::delete('/cities/{id}')
                ->middleware(AuthMiddleware::class)
                ->action(\App\Transport\Http\Handler\City\DeleteHandler::class)
                ->name('city/delete'),

            Route::get('/cities/{id}/stocks')
                ->action(GetCityStockListHandler::class)
                ->name('city/stock-list'),

            Route::get('/stocks')
                ->action(GetListHandler::class)
                ->name('stock/list'),

            Route::get('/stocks/{id}')
                ->middleware(FindByIdCacheMiddleware::class)
                ->action(GetByIdHandler::class)
                ->name('stock/by-id'),

            Route::post('/stocks')
                ->middleware(AuthMiddleware::class)
                ->action(CreateHandler::class)
                ->name('stock/create'),

            Route::patch('/stocks/{id}')
                ->middleware(AuthMiddleware::class)
                ->action(UpdateHandler::class)
                ->name('stock/update'),

            Route::delete('/stocks/{id}')
                ->middleware(AuthMiddleware::class)
                ->action(DeleteHandler::class)
                ->name('stock/delete'),

            Route::post('/stocks/nearby')
                ->middleware(FindNearestCacheMiddleware::class)
                ->action(FindNearbyStockHandler::class)
                ->name('stock/find-nearby'),
        ),

];
