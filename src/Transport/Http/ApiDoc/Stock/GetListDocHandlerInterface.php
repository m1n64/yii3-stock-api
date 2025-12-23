<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Stock;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/stocks',
    operationId: 'getStocksList',
    description: 'Returns a paginated list of stocks.',
    summary: 'List all stocks',
    tags: ['Stocks'],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/TraceparentHeader"),
        new OA\Parameter(ref: "#/components/parameters/ContentTypeHeader"),
        new OA\Parameter(
            name: 'page',
            description: 'Page number for results pagination',
            in: 'query',
            schema: new OA\Schema(type: 'integer', default: 1)
        ),
        new OA\Parameter(
            name: 'per_page',
            description: 'Number of items per page',
            in: 'query',
            schema: new OA\Schema(type: 'integer', default: 20)
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Paginated list of stocks",
            content: new OA\JsonContent(
                allOf: [
                    new OA\Schema(ref: "#/components/schemas/ApiResponse"),
                    new OA\Schema(
                        properties: [
                            new OA\Property(
                                property: "data",
                                allOf: [
                                    new OA\Schema(ref: "#/components/schemas/PaginationMeta"),
                                    new OA\Schema(
                                        properties: [
                                            new OA\Property(
                                                property: "items",
                                                type: "array",
                                                items: new OA\Items(ref: "#/components/schemas/StockDto")
                                            )
                                        ]
                                    )
                                ]
                            )
                        ]
                    )
                ]
            )
        ),
        new OA\Response(response: 500, description: 'Internal server error')
    ]
)]
interface GetListDocHandlerInterface
{

}
