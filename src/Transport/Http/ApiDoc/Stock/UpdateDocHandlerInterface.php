<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Stock;

use OpenApi\Attributes as OA;

#[OA\Patch(
    path: '/stocks/{id}',
    operationId: 'updateStock',
    summary: 'Update an existing stock',
    security: [['bearerAuth' => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(ref: "#/components/schemas/UpdateStockRequest")
    ),
    tags: ['Stocks'],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/TraceparentHeader"),
        new OA\Parameter(ref: "#/components/parameters/ContentTypeHeader"),
        new OA\Parameter(
            name: 'id',
            description: 'ID of the stock to update',
            in: 'path',
            required: true,
            schema: new OA\Schema(type: 'string', format: 'uuid')
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Stock updated successfully",
            content: new OA\JsonContent(
                allOf: [
                    new OA\Schema(ref: "#/components/schemas/ApiResponse"),
                    new OA\Schema(properties: [
                        new OA\Property(property: "data", ref: "#/components/schemas/StockDto")
                    ])
                ]
            )
        ),
        new OA\Response(response: 401, description: "Unauthorized"),
        new OA\Response(response: 404, description: "Stock not found"),
        new OA\Response(
            response: 422,
            description: "Unprocessable Entity (Validation Errors)",
            content: new OA\JsonContent(
                ref: "#/components/schemas/ValidationErrorResponse"
            )
        ),
    ]
)]
interface UpdateDocHandlerInterface
{

}
