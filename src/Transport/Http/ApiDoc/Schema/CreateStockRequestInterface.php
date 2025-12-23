<?php
declare(strict_types=1);

namespace App\Transport\Http\ApiDoc\Schema;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "CreateStockRequest",
    title: "Stock Model",
    description: "Data transfer object representing a stock entity",
    required: ["city_id", "address", "lat", "lng"],
    properties: [
        new OA\Property(property: "city_id", type: "string", format: "uuid"),
        new OA\Property(property: "address", type: "string", example: "Baker st. 221b"),
        new OA\Property(property: "lat", type: "float", example: "51.523767"),
        new OA\Property(property: "lng", type: "float", example: "-0.1585557"),
    ]
)]
interface CreateStockRequestInterface
{

}
