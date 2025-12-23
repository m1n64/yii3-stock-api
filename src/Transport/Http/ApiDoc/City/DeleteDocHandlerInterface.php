<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\City;

use OpenApi\Attributes as OA;

#[OA\Delete(
    path: '/cities/{id}',
    operationId: 'deleteCity',
    summary: 'Delete a city',
    security: [['bearerAuth' => []]],
    tags: ['Cities'],
    parameters: [
        new OA\Parameter(ref: "#/components/parameters/TraceparentHeader"),
        new OA\Parameter(ref: "#/components/parameters/ContentTypeHeader"),
        new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'string', format: 'uuid'))
    ],
    responses: [
        new OA\Response(
            ref: '#/components/schemas/ApiResponse',
            response: 200,
            description: "City deleted successfully"
        ),
        new OA\Response(response: 404, description: "City not found")
    ]
)]
interface DeleteDocHandlerInterface
{

}
