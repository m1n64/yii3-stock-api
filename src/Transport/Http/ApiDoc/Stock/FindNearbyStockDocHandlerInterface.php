<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Stock;

use OpenApi\Attributes as OA;

#[OA\Post(
    path: '/stocks/nearby',
    operationId: 'getNearbyStock',
    summary: 'Find the nearest stock by coordinates',
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/NearbyStockRequest")
    ),
    tags: ['Stocks'],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/TraceparentHeader"),
        new OA\Parameter(ref: "#/components/parameters/ContentTypeHeader")
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "The closest stock found",
            content: new OA\JsonContent(
                allOf: [
                    new OA\Schema(ref: "#/components/schemas/ApiResponse"),
                    new OA\Schema(properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/NearbyStockDto")
                    ])
                ]
            )
        ),
        new OA\Response(
            response: 422,
            description: "Unprocessable Entity (Validation Errors)",
            content: new OA\JsonContent(
                ref: "#/components/schemas/ValidationErrorResponse"
            )
        ),
        new OA\Response(response: 404, description: "No stocks found")
    ]
)]
interface FindNearbyStockDocHandlerInterface
{

}
