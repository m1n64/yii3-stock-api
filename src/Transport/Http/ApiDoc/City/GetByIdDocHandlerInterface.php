<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\City;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/cities/{id}',
    operationId: 'getCityById',
    summary: 'Get city details by ID',
    tags: ['Cities'],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/TraceparentHeader"),
        new OA\Parameter(ref: "#/components/parameters/ContentTypeHeader"),
        new OA\Parameter(
            name: "id",
            description: "City UUID or ID",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "string", format: "uuid")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "City details retrieved successfully",
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
        new OA\Response(response: 404, description: "City not found")
    ]
)]
interface GetByIdDocHandlerInterface
{

}
