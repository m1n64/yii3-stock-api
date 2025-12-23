<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Stock;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/stocks/{id}',
    operationId: 'getStockById',
    summary: 'Get stock details by ID',
    tags: ['Stocks'],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/TraceparentHeader"),
        new OA\Parameter(ref: "#/components/parameters/ContentTypeHeader"),
        new OA\Parameter(
            name: "id",
            description: "Stock UUID or ID",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "string", format: "uuid")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Stock details retrieved successfully",
            content: new OA\JsonContent(
                allOf: [
                    new OA\Schema(ref: "#/components/schemas/ApiResponse"),
                    new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "data",
                                ref: "#/components/schemas/CityDto"
                            )
                        ],
                        type: "object"
                    )
                ]
            )
        ),
        new OA\Response(response: 404, description: "Stock not found")
    ]
)]
interface GetByIdDocHandlerInterface
{

}
